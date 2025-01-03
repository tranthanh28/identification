<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-sm-12 col-md-6">
        <app-breadcrumb page-title="Upload File" :directory="$t('datatables')" :icon="'grid'"/>
      </div>
      <div class="col-sm-12 col-md-6 breadcrumb-side-button">
        <div class="float-md-right mb-3 mb-sm-3 mb-md-0">
          <button type="button"
                  class="btn btn-primary btn-with-shadow"
                  data-toggle="modal"
                  @click="dialogFormVisible = true">
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
          prop="status">
      </el-table-column>
      <el-table-column
          label="created"
          prop="created_at">
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
              @click="handleDownload(scope.row)">Download
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog title="Upload file" :visible.sync="dialogFormVisible">
      <el-form :model="form">
        <el-form-item label="Model" :label-width="formLabelWidth">
          <el-select v-model="form.model" placeholder="Select">
            <el-option
                v-for="item in options"
                :key="item.value"
                :label="item.label"
                :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="File" :label-width="formLabelWidth">
          <el-upload
              action=""
              class="upload-demo"
              style="height: 50px;"
              ref="upload"
              :on-change="handleImport"
              :auto-upload="false">
            <el-button slot="trigger" size="small" type="primary">Select file</el-button>
          </el-upload>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
    <el-button @click="dialogFormVisible = false">Cancel</el-button>
    <el-button type="primary" @click="handleSubmit">Submit</el-button>
  </span>
    </el-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      //Pagination
      totalPages: 0,
      perPage: 10,
      currentPage: 1,
      search: '',
      dialogTableVisible: false,
      dialogFormVisible: false,
      form: {
        model: "1",
        post_ids: "",
      },
      options: [
        {
          value: '1',
          label: 'Model 1'
        }, {
          value: '2',
          label: 'Model 2'
        }, {
          value: '3',
          label: 'Model 3'
        }
      ],
      formLabelWidth: '120px',
      fileContent: '',
      errors: "",
      data: [],
    }
  },
  created() {
    this.getList(this.page)
  },
  methods: {
    handleSubmit() {
      this.startLoading()
      this.$refs.upload.clearFiles();
      let formData = new FormData();
      formData.append('file', this.uploadFile);
      formData.append('model', this.form.model);
      // this.form.post_ids = this.fileContent
      axios.post('/api/mr',
          formData,
          {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          }
      ).then((response) => {
        this.stopLoading()
        this.getList()
        this.dialogFormVisible = false
        this.form = {
          name: '',
          post_ids: "",
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
    handleImport(file) {
      this.uploadFile = file.raw
    },
    handleDownload(row) {
      console.log(row)
      let dataDownload = {
        file_name: row.output_file_name
      }
      axios.post('/api/mr/export-excel', dataDownload, {
        responseType: 'blob'
      }).then((response) => {
        const url = URL.createObjectURL(new Blob([response.data], {
          type: 'application/vnd.ms-excel'
        }))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', dataDownload.file_name)
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
    getList() {
      this.startLoading()
      axios.get(`/api/mr`).then((response) => {
        this.stopLoading()
        this.data = response.data.data
        // this.totalPages = response.data.data.total
        // this.perPage = response.data.data.per_page
        // this.currentPage = response.data.data.current_page
      }).catch((errors) => {
        this.stopLoading()
        this.handleErrorNotPermission(errors)
      })
    }
  }

}
</script>
