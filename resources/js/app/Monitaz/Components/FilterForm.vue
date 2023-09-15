<template>
  <el-form :inline="true" :model="formFilter" class="demo-form-inline">
    <el-form-item label="Search">
      <el-input v-model="formFilter.search" placeholder="Search name"></el-input>
    </el-form-item>
    <el-form-item label="Status">
      <el-select v-model="formFilter.status" multiple placeholder="">
        <el-option label="Pending" value="0"></el-option>
        <el-option label="Process" value="1"></el-option>
        <el-option label="Done" value="2"></el-option>
      </el-select>
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
