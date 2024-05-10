<template>
  <div>
    <el-tabs v-model="activeName" @tab-click="handleClickTab">
      <el-tab-pane label="Facebook" name="facebook">
        <el-card shadow="hover" style="border-radius: 30px">
          <div class="text item">
            <el-row type="flex">
              <el-col :span="10">
                <div>Name Config</div>
              </el-col>
              <el-col>
                <div>: {{ form.name }}</div>
              </el-col>
            </el-row>
          </div>
          <div class="text item">
            <el-row type="flex">
              <el-col :span="10">
                <div>Phone</div>
              </el-col>
              <el-col>
                <div>: {{ form.phone }}</div>
              </el-col>
            </el-row>
          </div>
          <div class="text item">
            <el-row type="flex">
              <el-col :span="10">
                <div>UID Facebook</div>
              </el-col>
              <el-col>
                <div>: {{ form.facebook_uid }}</div>
              </el-col>
            </el-row>
          </div>
          <div class="text item">
            <el-row type="flex">
              <el-col :span="10">
                <div>Username Tiktok</div>
              </el-col>
              <el-col>
                <div>: {{ form.tiktok_unique }}</div>
              </el-col>
            </el-row>
          </div>
        </el-card>
        <div class="info-user-fb group-info" v-if="!isObjectEmpty(data_audience)">
          <div class="header-info-user-fb d-flex align-content-center">
            <el-avatar :size="30" src="https://empty" @error="errorHandler">
              <img src="https://cube.elemecdn.com/e/fd/0fc7d20532fdaf769a25683617711png.png"/>
            </el-avatar>
            <div style="padding-left: 10px">{{ data_audience.from_name }}</div>

          </div>
          <el-card shadow="hover" style="border-radius: 30px; padding-top: 5px; background: #d3dce6;">
            <div class="text item">
              <el-row type="flex">
                <el-col :span="12">
                  <div>Tên người dùng</div>
                </el-col>
                <el-col>
                  <div>: {{data_audience.from_name}}</div>
                </el-col>
              </el-row>
            </div>
            <div class="text item">
              <el-row type="flex">
                <el-col :span="12">
                  <div>Phone</div>
                </el-col>
                <el-col>
                  <div>: {{form.phone}}</div>
                </el-col>
              </el-row>
            </div>
            <div class="text item">
              <el-row type="flex">
                <el-col :span="12">
                  <div>Giới tính</div>
                </el-col>
                <el-col>
                  <div>: {{ data_audience.gender }}</div>
                </el-col>
              </el-row>
            </div>
            <div class="text item">
              <el-row type="flex">
                <el-col :span="12">
                  <div>Tình trạng mối quan hệ</div>
                </el-col>
                <el-col>
                  <div>: {{data_audience.relation_ship}}</div>
                </el-col>
              </el-row>
            </div>
            <div class="text item">
              <el-row type="flex">
                <el-col :span="12">
                  <div>Học vấn</div>
                </el-col>
                <el-col>
                  <div>: {{data_audience.education_type}}</div>
                </el-col>
              </el-row>
            </div>
            <div class="text item">
              <el-row type="flex">
                <el-col :span="12">
                  <div>Vị trí</div>
                </el-col>
                <el-col>
                  <div>: {{data_audience.province}}</div>
                </el-col>
              </el-row>
            </div>
            <div class="text item">
              <el-row type="flex">
                <el-col :span="12">
                  <div>Trạng thái nghề nghiệp</div>
                </el-col>
                <el-col>
                  <div>: {{data_audience.job}}</div>
                </el-col>
              </el-row>
            </div>
          </el-card>
        </div>
        <div class="has-post group-info">
          <div class="header-group">Bài Đăng Ghi Nhận Trên Hệ Thống Social Listening
            <el-tag type="info">
              {{ post_is_recorded_on_social_listening.length }}
            </el-tag>
          </div>
          <el-card shadow="hover" style="border-radius: 30px; padding-top: 5px; background: #d3dce6;">
            <div v-for="(item,index) in post_is_recorded_on_social_listening">
              <div class="text item">
                <el-row type="flex">
                  <el-col :span="2">
                    <div>{{ index }}.</div>
                  </el-col>
                  <el-col>
                    <div>
                      <el-link :href="'https://www.facebook.com/'+ item.fb_post_id" target="_blank" type="success">
                        {{ item.fb_post_id }}
                      </el-link>
                    </div>
                  </el-col>
                </el-row>
              </div>
            </div>
          </el-card>
        </div>
        <div class="has-join-group group-info">
          <div class="header-group">Số Group Facebook Đã Tham Gia
            <el-tag type="info">
              {{ user_has_joined_group.length }}
            </el-tag>
          </div>
          <el-card shadow="hover" style="border-radius: 30px; padding-top: 5px; background: #d3dce6;">
            <div v-for="(item,index) in user_has_joined_group">
              <div class="text item">
                <el-row type="flex">
                  <el-col :xl="2" :xs="4" :span="2">
                    <div>{{ index }}.</div>
                  </el-col>
                  <el-col :xl="8" :sm="12" :md="14">
                    <el-link :href="item.url" target="_blank" type="success"><div>{{ item?.id }}</div></el-link>
                  </el-col>
                  <el-col>
                    <div> - {{ item?.name }}</div>
                  </el-col>
<!--                  <el-col>-->
<!--                    <div>-->
<!--                      <el-link :href="item.url" target="_blank" type="success">-->
<!--                        {{ item?.id }} - {{ item?.name }}-->
<!--                    </div>-->
<!--                  </el-col>-->
                </el-row>
              </div>
            </div>
          </el-card>
        </div>
      </el-tab-pane>
      <el-tab-pane label="Tiktok" name="tiktok">
        <el-card shadow="hover" style="border-radius: 30px">
          <div class="text item">
            <el-row type="flex">
              <el-col :span="10">
                <div>Name Config</div>
              </el-col>
              <el-col>
                <div>: {{ form.name }}</div>
              </el-col>
            </el-row>
          </div>
          <div class="text item">
            <el-row type="flex">
              <el-col :span="10">
                <div>Phone</div>
              </el-col>
              <el-col>
                <div>: {{ form.phone }}</div>
              </el-col>
            </el-row>
          </div>
          <div class="text item">
            <el-row type="flex">
              <el-col :span="10">
                <div>UID Facebook</div>
              </el-col>
              <el-col>
                <div>: {{ form.facebook_uid }}</div>
              </el-col>
            </el-row>
          </div>
          <div class="text item">
            <el-row type="flex">
              <el-col :span="10">
                <div>Username Tiktok</div>
              </el-col>
              <el-col>
                <div>: {{ form.tiktok_unique }}</div>
              </el-col>
            </el-row>
          </div>
        </el-card>
        <div class="info-user-tiktok group-info" v-if="!isObjectEmpty(tiktok_user_information)">
          <div class="header-info-user d-flex align-content-center">
            <el-avatar :size="30" :src="form?.tiktok_avatar" @error="errorHandler">
              <img src="https://cube.elemecdn.com/e/fd/0fc7d20532fdaf769a25683617711png.png"/>
            </el-avatar>
            <div style="padding-left: 10px">{{tiktok_user_information?.tiktok_nickname}}</div>

          </div>
          <el-card shadow="hover" style="border-radius: 30px; padding-top: 5px; background: #d3dce6;">
            <div class="text item">
              <el-row type="flex">
                <el-col :span="12">
                  <div>Phone</div>
                </el-col>
                <el-col>
                  <div>: {{ form.phone }}</div>
                </el-col>
              </el-row>
            </div>
            <div class="text item">
              <el-row type="flex">
                <el-col :span="12">
                  <div>Username</div>
                </el-col>
                <el-col>
                  <div>: {{ form.tiktok_unique }}</div>
                </el-col>
              </el-row>
            </div>
          </el-card>
        </div>
        <div class="group-info">
          <div class="header-group">Thông Tin Cửa Hàng
          </div>
          <el-card shadow="hover" style="border-radius: 30px; padding-top: 5px; background: #d3dce6;">
            <div class="text item">
              <el-row type="flex">
                <el-col :span="12">
                  <div>Shop Id</div>
                </el-col>
                <el-col>
                  <div>: {{information_shop?.seller_id}}</div>
                </el-col>
              </el-row>
            </div>
            <div class="text item">
              <el-row type="flex">
                <el-col :span="12">
                  <div>Tên cửa hàng</div>
                </el-col>
                <el-col>
                  <div>: {{information_shop?.seller_name}}</div>
                </el-col>
              </el-row>
            </div>
            <div class="text item">
              <el-row type="flex">
                <el-col :span="12">
                  <div>Tổng sản phẩm</div>
                </el-col>
                <el-col>
                  <div>: {{information_shop?.product_count}}</div>
                </el-col>
              </el-row>
            </div>
            <div class="text item">
              <el-row type="flex">
                <el-col :span="12">
                  <div>Sản phẩm đã bán</div>
                </el-col>
                <el-col>
                  <div>: {{information_shop?.sold_count}}</div>
                </el-col>
              </el-row>
            </div>
            <div class="text item">
              <el-row type="flex">
                <el-col :span="12">
                  <div>Điểm đánh giá</div>
                </el-col>
                <el-col>
                  <div>: {{information_shop?.shop_rating}}</div>
                </el-col>
              </el-row>
            </div>
            <div class="text item">
              <el-row type="flex">
                <el-col :span="12">
                  <div>Số đánh giá</div>
                </el-col>
                <el-col>
                  <div>: {{information_shop?.review_count}}</div>
                </el-col>
              </el-row>
            </div>
          </el-card>
        </div>
        <div class="review-tiktok group-info">
          <div class="header-group">Đánh Giá Cửa Hàng
          </div>
          <el-card shadow="hover" style="border-radius: 30px; padding-top: 5px; background: #d3dce6;">
            <div v-for="(item,index) in tiktok_shop_review">
              <div class="item">
                <div class="header-item d-flex justify-content-between">
                  <div class="left-header-item d-flex justify-content-start">
                    <div class="avatar-review">
                      <el-avatar :size="40"></el-avatar>
                    </div>
                    <div class="name-user-review">
                      <div>{{ item.review_name }}</div>
                      <div>{{item.review_created}}</div>
                    </div>
                  </div>
                  <div class="right-header-item">
                    <el-tag type="info">{{item.reivew_is_anonymous ==1 ? "Ẩn danh" : "Công khai"}}</el-tag>
                  </div>
                </div>
                <div class="rate-item">
                  <el-rate
                      v-model="item.review_rating"
                      disabled
                      text-color="#ff9900">
                  </el-rate>
                </div>
                <div class="content-item">{{item.review_content}}</div>
                <el-divider></el-divider>
              </div>
            </div>
          </el-card>
        </div>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>
<script>
export default {
  name: 'Dialog-Detail-Identification',
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
          tiktok_avatar: '',
        }
      }
    },
    data_audience: {
      type: Object,
      default() {
        return null;
      }
    },
    user_has_joined_group: {
      type: Array,
    },
    post_is_recorded_on_social_listening: {
      type: Array,
    },
    tiktok_shop_review: {
      type: Array,
    },
    information_shop: {
      type: Object
    },
    textSubmit: {
      type: String
    },
    tiktok_user_information: {
      type: Object,
    }
  },
  data() {
    return {
      activeName: 'facebook',
      formLabelWidth: '120px',
    }
  },
  methods: {
    handleClickTab(tab, event) {
      console.log(tab, event);
    },
    submit() {
      this.$emit('submit')
    },
    errorHandler() {
      return true
    },
    isObjectEmpty(objectName) {
      return Object.keys(objectName).length === 0
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

.info-user-fb {
  padding-top: 20px;
}
.name-user-review {
  margin-left: 5px;
}
.group-info {
  margin-top: 25px;
}
.header-group {
  font-size: 15px;
}
</style>
