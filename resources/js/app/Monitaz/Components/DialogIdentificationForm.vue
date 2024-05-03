<template>
  <div>
    <el-form :model="form" ref="ruleForm">
      <el-form-item label="Name" :label-width="formLabelWidth" prop="name">
        <el-input v-model="form.name" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="Phone" :label-width="formLabelWidth" prop="phone">
        <el-input v-model="form.phone" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="Facebook UID" :label-width="formLabelWidth" prop="facebook_uid">
        <el-input v-model="form.facebook_uid" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="Unique Name" :label-width="formLabelWidth" prop="tiktok_unique">
        <el-input v-model="form.tiktok_unique" autocomplete="off"></el-input>
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer d-flex justify-content-center" style="margin-top: 60px;">
                <el-button type="primary" @click="submit()">{{textSubmit}}</el-button>
            </span>
  </div>
</template>
<script>
export default {
  name: 'Dialog-Identification',
  props: {
    form: {
      type: Object,
      default() {
        return {
          id: '',
          name: '',
          phone: '',
          facebook_uid: '',
          tiktok_unique: '',
        }
      }
    },
    textSubmit: {
      type: String
    }
  },
  data() {
    return {
      formLabelWidth: '120px',
    }
  },
  methods: {
    submit() {
      this.$emit('submit')
    },
    handleImport(file) {
      this.uploadFile = file
      let reader = new FileReader()
      reader.readAsText(this.uploadFile.raw)
      reader.onload = async (e) => {
        try {
          this.form.content_file = e.target.result
        } catch (err) {
          console.log(`Load JSON file error: ${err.message}`)
        }
      }
    },
  }

}
</script>
<style scoped>
#comment {
  position: absolute;
  bottom: -44px;
  color: red;
  font-style: italic;
}
</style>
