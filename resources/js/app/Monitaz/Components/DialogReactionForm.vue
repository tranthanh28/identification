<template>
  <div>
    <el-form :model="form" :rules="rules" ref="ruleForm">
      <el-form-item label="Name" :label-width="formLabelWidth" prop="name">
        <el-input v-model="form.name" autocomplete="off"></el-input>
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
    <span slot="footer" class="dialog-footer d-flex justify-content-center" style="margin-top: 60px;">
                <el-button type="primary" @click="submit()">Submit</el-button>
            </span>
  </div>
</template>
<script>
export default {
  name: 'Dialog-Role',
  props: {
    form: {
      type: Object,
      default() {
        return {
          id: '',
          name: '',
          post_ids: '',
        }
      }
    }
  },
  data() {
    return {
      formLabelWidth: '120px',
      rules: {
        name: [
          {required: true, message: 'Please input name', trigger: 'blur'},
        ],
      }
    }
  },
  methods: {
    submit() {
      this.$refs['ruleForm'].validate((valid) => {
        this.$refs.upload.clearFiles();
        if (valid) {
          this.$emit('submit')
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    handleImport(file) {
      this.uploadFile = file
      let reader = new FileReader()
      reader.readAsText(this.uploadFile.raw)
      reader.onload = async (e) => {
        try {
          this.form.post_ids = e.target.result
        } catch (err) {
          console.log(`Load JSON file error: ${err.message}`)
        }
      }
    },
  }

}
</script>
<style scoped></style>
