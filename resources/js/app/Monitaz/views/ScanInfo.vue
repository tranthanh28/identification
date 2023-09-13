<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-sm-12 col-md-6">
        <app-breadcrumb page-title="Scan Info" :directory="$t('datatables')" :icon="'grid'"/>
      </div>
      <div class="col-sm-12 col-md-6 breadcrumb-side-button">
        <div class="float-md-right mb-3 mb-sm-3 mb-md-0">
          <button type="button"
                  class="btn btn-primary btn-with-shadow"
                  data-toggle="modal"
                  @click="handleCreate">
            {{ $t('add') }}
          </button>
        </div>
      </div>
    </div>
    <el-table
        :data="data.filter(data => !search || data.name.toLowerCase().includes(search.toLowerCase()))"
        style="width: 100%">
      <el-table-column
          label="name"
          prop="name">
      </el-table-column>
      <el-table-column
          label="status"
          prop="status"
          :filters="[{ text: 'Pending', value: '0' }, { text: 'Process', value: '1' }, { text: 'Done', value: '2' }]"
          :filter-method="filterTag"
          filter-placement="bottom-end">
        <template slot-scope="scope">
          <el-tag v-if="scope.row.status == 2" type="success">Done</el-tag>
          <el-tag v-if="scope.row.status == 1" type="info">Process</el-tag>
          <el-tag v-if="scope.row.status == 0" type="warning">Pending</el-tag>
        </template>
      </el-table-column>
      <el-table-column
          label="Pass Day"
          prop="pass_day">
      </el-table-column>
      <el-table-column
          label="created"
          prop="created_at">
      </el-table-column>
      <el-table-column
          label="updated"
          prop="updated_at">
      </el-table-column>
      <el-table-column
          align="right">
        <template slot="header" slot-scope="scope">
          <el-input
              v-model="search"
              size="mini"
              placeholder="Type to search"/>
        </template>
        <template slot-scope="scope">
          <el-button
              size="mini"
              :disabled="scope.row.status == 1"
              @click="handleEdit(scope.row)"><i class="el-icon-edit"></i>Edit
          </el-button>
          <el-button
              :disabled="scope.row.status != 2"
              size="mini"
              @click="handleDowload(scope.row)"><i class="el-icon-download"></i>Dowload
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-pagination
        background
        layout="prev, pager, next"
        @current-change="handleCurrentChange"
        :current-page.sync="currentPage"
        :page-size="perPage"
        :total="totalPages">
    </el-pagination>

    <el-dialog :title="titleDialog" :visible.sync="dialogFormVisible">
      <DialogScanForm :form="form" @submit="submit"></DialogScanForm>
    </el-dialog>
  </div>
</template>

<script>
import DialogScanForm from "@/app/Monitaz/Components/DialogScanForm";
export default {
  components: {DialogScanForm},
  data() {
    return {
      titleDialog: "Create",
      //Pagination
      page:1,
      totalPages: 0,
      perPage: 10,
      currentPage: 1,

      search: '',
      dialogTableVisible: false,
      dialogFormVisible: false,
      form: {
        id: "",
        name: '',
        pass_day: '',
        list_fb_ids: "",
      },
      formLabelWidth: '120px',
      errors: "",
      data: [],
    }
  },
  created() {
    let urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('page')) {
      this.page = urlParams.get('page');
    }

    this.getList(this.page)
  },
  methods: {
    handleCurrentChange(val) {
      this.getList(val)
    },
    filterTag(value, row) {
      return row.status == value;
    },
    submit() {
      this.startLoading()
      if (this.form.id) {
        this.handleUpdate()
      } else {
        this.handleAdd()
      }
    },
    handleEdit(row) {
      if (row.status == 1) return
      this.titleDialog = 'Edit'
      this.dialogFormVisible = true
      this.form.id = row.id
      this.form.name = row.name
      this.form.pass_day = row.pass_day
      this.form.list_fb_ids = ""
    },
    handleCreate() {
      this.titleDialog = 'Create'
      this.dialogFormVisible = true
      this.form.id = null
      this.form.name = ''
      this.form.pass_day = 60
      this.form.list_fb_ids = ""
    },
    handleAdd() {
      axios.post('/api/scan-info', this.form).then((response) => {
        this.stopLoading()
        this.getList()
        this.dialogFormVisible = false
        this.form = {
          name: '',
          list_fb_ids: "",
          pass_day: 60,
        }
      }).catch((error) => {
        this.stopLoading()
        this.$notify.error({
          title: 'Error',
          message: 'failed'
        });
        this.errors = error.response.data.errors;
      })
    },
    handleUpdate() {
      axios.put(`/api/scan-info/${this.form.id}`, this.form).then((response) => {
        this.stopLoading()
        this.getList()
        this.dialogFormVisible = false
        this.form = {
          id:'',
          name: '',
          pass_day: '',
          list_fb_ids: "",
        }
      }).catch((error) => {
        this.stopLoading()
        this.$notify.error({
          title: 'Error',
          message: 'failed'
        });
        this.errors = error.response.data.errors;
      })
    },
    handleDowload(row) {
      let dataDowload = {
        file_name: row.file_name
      }
      axios.post('/api/scan-info/export-excel', dataDowload, {
        responseType: 'blob'
      }).then((response) => {
        const url = URL.createObjectURL(new Blob([response.data], {
          type: 'application/vnd.ms-excel'
        }))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', dataDowload.file_name)
        document.body.appendChild(link)
        link.click()
      }).catch((error) => {
        this.stopLoading()
        this.$notify.error({
          title: 'Error',
          message: 'failed'
        });
      })
    },
    getList(page = 1) {
      this.startLoading()
      axios.get(`/api/scan-info?page=${page}`).then((response) => {
        this.stopLoading()
        this.data = response.data.data.data
        this.totalPages = response.data.data.total
        this.perPage = response.data.data.per_page
        this.currentPage = response.data.data.current_page
      }).catch((errors) => {
        this.stopLoading()
        this.handleErrorNotPermission(errors)
      })
    }
  }

}
</script>
