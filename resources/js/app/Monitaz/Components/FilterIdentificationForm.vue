<template>
  <el-form :inline="true" :model="formFilter" class="demo-form-inline">
    <el-form-item label="Name Config">
      <el-input v-model="formFilter.name" placeholder="Nhập Name Config"></el-input>
    </el-form-item>
    <el-form-item label="Phone">
      <el-input v-model="formFilter.phone" placeholder="Nhập Phone"></el-input>
    </el-form-item>
    <el-form-item label="UID Facebook">
      <el-input v-model="formFilter.facebook_uid" placeholder="Nhập UID Facebook"></el-input>
    </el-form-item>
    <el-form-item label="Username Tiktok">
      <el-input v-model="formFilter.tiktok_unique" placeholder="Nhập Username Tiktok"></el-input>
    </el-form-item>
    <el-form-item>
      <el-button type="primary" @click="onSubmit " icon="el-icon-search">Search</el-button>
    </el-form-item>
  </el-form>
</template>
<script>
export default {
  props: {
    formFilter: {
      type: Object,
      default() {
        return {
          search: '',
          status: []
        }
      }
    }
  },
  data() {
    return {}
  },
  methods: {
    onSubmit() {
      this.$emit('filter');
    }
  }
}
</script>
