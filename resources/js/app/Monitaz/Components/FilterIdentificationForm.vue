<template>
  <el-form :inline="true" :model="formFilter" class="demo-form-inline">
    <el-form-item label="Name Config">
      <el-input v-model="formFilter.name" placeholder=""></el-input>
    </el-form-item>
    <el-form-item label="Phone">
      <el-input v-model="formFilter.phone" placeholder=""></el-input>
    </el-form-item>
    <el-form-item label="UID Facebook">
      <el-input v-model="formFilter.facebook_uid" placeholder=""></el-input>
    </el-form-item>
    <el-form-item label="Username Tiktok">
      <el-input v-model="formFilter.tiktok_unique" ></el-input>
    </el-form-item>
    <el-form-item>
      <el-button type="primary" @click="onSubmit">Filter</el-button>
    </el-form-item>
  </el-form>
</template>
<script>
export default {
  props:{
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
    return {
    }
  },
  methods: {
    onSubmit() {
      this.$emit('filter');
    }
  }
}
</script>
