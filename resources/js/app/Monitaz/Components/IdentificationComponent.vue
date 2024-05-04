<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-sm-12 col-md-6">
        <app-breadcrumb :page-title="title" :directory="$t('datatables')" :icon="'grid'"/>
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
    <FilterIdentificationForm :formFilter="formFilter" @filter="filterSearchForm"></FilterIdentificationForm>

    <el-table
        :data="data.filter(data => !search || data.name.toLowerCase().includes(search.toLowerCase()))"
        style="width: 100%">
      <el-table-column
          type="index"
          width="80">
      </el-table-column>
      <el-table-column
          label="Name Config test"
          prop="name">
      </el-table-column>
      <el-table-column
          label="Phone"
          prop="phone">
      </el-table-column>
      <el-table-column
          label="UID Facebook"
          prop="facebook_uid">
      </el-table-column>
      <el-table-column
          label="Username Tiktok"
          prop="tiktok_unique">
      </el-table-column>
      <el-table-column
          label="Status"
          prop="status"
          :filters="[{ text: 'Pending', value: '0' }, { text: 'Processing', value: '1' }, { text: 'Success', value: '2' }]"
          :filter-method="filterTag"
          filter-placement="bottom-end">
        <template slot-scope="scope">
          <el-tag v-if="scope.row.status == 2" type="success" effect="dark">Success</el-tag>
          <el-tag v-if="scope.row.status == 1" type="info" effect="dark">Processing</el-tag>
          <el-tag v-if="scope.row.status == 0" type="warning" effect="dark">Pending</el-tag>
        </template>
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
              :disabled="scope.row.status !== 2"
              @click="handleView(scope.row)"><i class="el-icon-view"></i>
          </el-button>
          <el-button
              size="mini"
              :disabled="scope.row.status != 2"
              @click="handleEdit(scope.row)"><i class="el-icon-edit"></i>
          </el-button>
          <el-button
              size="mini"
              :disabled="scope.row.status !== 2"
              @click="handleDelete(scope.row)"><i class="el-icon-delete-solid"></i>
          </el-button>
          <el-button
              :disabled="scope.row.status != 2"
              size="mini"
              @click="handleDowload(scope.row)"><i class="el-icon-download"></i>
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
      <DialogIdentificationForm :form="form" :textSubmit="textSubmit" @submit="submit"></DialogIdentificationForm>
    </el-dialog>

    <el-dialog :title="titleDetailDialog" :visible.sync="dialogFormDetailVisible" style="border-radius: 20px">
      <DetailDialogIdentificationForm :form="formDetail" :data_audience="data_audience" :user_has_joined_group="user_has_joined_group" :post_is_recorded_on_social_listening="post_is_recorded_on_social_listening" :tiktok_shop_review="tiktok_shop_review" :information_shop="information_shop" :tiktok_user_information="tiktok_user_information"></DetailDialogIdentificationForm>
    </el-dialog>
  </div>
</template>

<script>
import DialogIdentificationForm from "@/app/Monitaz/Components/DialogIdentificationForm";
import DetailDialogIdentificationForm from "@/app/Monitaz/Components/DetailDialogIdentificationForm";
import FilterIdentificationForm from "@/app/Monitaz/Components/FilterIdentificationForm";
import StringMethod from "@/core/helpers/string/StringMethod";

export default {
  components: {DialogIdentificationForm, FilterIdentificationForm, DetailDialogIdentificationForm},
  props: {
    title: {
      type: String,
      require: true
    },
    urlApi: {
      type: String,
      require: true
    }
  },
  data() {
    return {
      formFilter: {
        name: '',
        phone: '',
        facebook_uid: '',
        tiktok_unique: ''
      },
      formWindowUrl: {
        search: '',
        status: '',
        page: ''
      },
      titleDialog: "Create",
      titleDetailDialog: "Thông tin chi tiết",
      textSubmit: "",
      //Pagination
      page: 1,
      totalPages: 0,
      perPage: 10,
      currentPage: 1,

      search: '',
      dialogTableVisible: false,
      dialogFormVisible: false,
      dialogFormDetailVisible: false,
      form: {
        id: "",
        name: '',
        phone: '',
        facebook_uid: "",
        tiktok_unique: "",
      },
      formDetail: {},
      data_audience: {},
      user_has_joined_group: [],
      post_is_recorded_on_social_listening: [],
      tiktok_shop_review: [],
      information_shop: {},
      tiktok_user_information: {},
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

    if (urlParams.has('name')) {
      this.formFilter.name = urlParams.get('name');
    }
    if (urlParams.has('phone')) {
      this.formFilter.phone = urlParams.get('phone');
    }
    if (urlParams.has('facebook_uid')) {
      this.formFilter.facebook_uid = urlParams.get('facebook_uid');
    }
    if (urlParams.has('tiktok_unique')) {
      this.formFilter.tiktok_unique = urlParams.get('tiktok_unique');
    }

    this.filterSearchForm(this.page)
  },
  methods: {
    filterSearchForm(page) {
      if (page == null) {
        this.page = 1
      }
      this.page = 1
      this.formWindowUrl = {...this.formFilter}
      this.formWindowUrl.page = this.page
      let pageTitle = document.title,
          query = StringMethod.objectToQueryString(this.formWindowUrl);
      window.history.pushState("", pageTitle, `?${query}`);
      this.getList(this.page)
    },
    handleCurrentChange(val) {
      this.formWindowUrl.page = val
      let pageTitle = document.title,
          query = StringMethod.objectToQueryString(this.formWindowUrl);
      window.history.pushState("", pageTitle, `?${query}`);
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
    handleView(row) {
      if (row.status !== 2) return
      this.titleDetailDialog = 'THÔNG TIN ĐỊNH DANH'
      this.dialogFormDetailVisible = true
      this.formDetail.id = row.id
      this.formDetail.name = row.name
      this.formDetail.phone = row.phone
      this.formDetail.facebook_uid = row.facebook_uid
      this.formDetail.tiktok_unique = row.tiktok_unique
      this.data_audience = row?.identification_detail?.data_audience ?? {}
      this.user_has_joined_group = row?.identification_detail?.user_has_joined_group ?? []
      this.post_is_recorded_on_social_listening = row?.identification_detail?.post_is_recorded_on_social_listening ?? []
      this.tiktok_shop_review = row?.identification_detail?.tiktok_shop_review ?? []
      this.information_shop = row?.identification_detail?.information_shop ?? {}
      this.tiktok_user_information = row?.identification_detail?.tiktok_user_information ?? {}
    },
    handleEdit(row) {
      if (row.status == 1) return
      this.titleDialog = 'SỬA ĐỊNH DANH'
      this.dialogFormVisible = true
      this.form.id = row.id
      this.form.name = row.name
      this.form.phone = row.phone
      this.form.facebook_uid = row.facebook_uid
      this.form.tiktok_unique = row.tiktok_unique
      this.textSubmit = "Cập nhập"
    },
    handleCreate() {
      this.titleDialog = 'THÊM MỚI ĐỊNH DANH'
      this.dialogFormVisible = true
      this.form.id = null
      this.form.name = ''
      this.form.phone = ''
      this.form.facebook_uid = ''
      this.form.tiktok_unique = ''
      this.textSubmit = "Thêm"
    },
    handleAdd() {
      axios.post(this.urlApi, this.form).then((response) => {
        this.stopLoading()
        this.getList()
        this.dialogFormVisible = false
        this.form = {
          id: '',
          name: "",
          phone: "",
          facebook_uid: "",
          tiktok_unique: "",
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
      axios.put(`${this.urlApi}/${this.form.id}`, this.form).then((response) => {
        this.stopLoading()
        this.getList()
        this.dialogFormVisible = false
        this.form = {
          id: '',
          name: "",
          phone: "",
          facebook_uid: "",
          tiktok_unique: "",
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
    handleDelete(row) {
      console.log('delete', row)
    },
    handleDowload(row) {
      let dataDowload = {
        file_name: row.file_name
      }
      axios.post(`${this.urlApi}/export-excel`, dataDowload, {
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

      let query = StringMethod.objectToQueryString(this.formWindowUrl);
      // if (status) {
      //   status = `&status=${status}`
      // }
      // if (search) {
      //   search = `&search=${search}`
      // }

      // this.data = [
      //   {
      //     "id" : 1,
      //     "name" : "test1",
      //     "phone" : "012312354232",
      //     "facebook_uid" : "123123",
      //     "tiktok_unique" : "test_!",
      //     "status" : 1,
      //   },
      //   {
      //     "id" : 2,
      //     "name" : "test2",
      //     "phone" : "012312354232",
      //     "facebook_uid" : "123123",
      //     "tiktok_unique" : "test_2",
      //     "status" : 2,
      //   },
      //   {
      //     "id" : 3,
      //     "name" : "test3",
      //     "phone" : "012312354232",
      //     "facebook_uid" : "123123",
      //     "tiktok_unique" : "test_3",
      //     "status" : 0,
      //   },
      // ]

      axios.get(`${this.urlApi}?page=${page}&${query}`).then((response) => {
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
