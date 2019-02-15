<template lang="pug">
  div
    v-card
      v-card-text
        v-form(ref="form", @submit.prevent="save")
          template(v-if="files.length")
            .preview-area.ma-2(v-for="file in files")
              img.thumbnail(v-if="file.thumb", :src="file.thumb")
              .file.pa-2(v-else)
                v-icon.mr-1 insert_drive_file
                span {{ file.name }}
          template(v-else)
            .preview-area.ma-2(v-if="!isNew")
              img.thumbnail(v-if="upload.public_thumb_path", :src="upload.public_thumb_path")
              .file.pa-2(v-else)
                v-icon.mr-1 insert_drive_file
                span {{ upload.name }}
          VueUploadComponent(
            ref="uploader",
            v-model="files",
            :multiple="false",
            :post-action="`${apiUri}/uploads`",
            :drop="true",
            :drop-directory="true",
            @input-file="onFileChange"
          )
            v-btn(color="accent")
              v-icon.mr-1 add
              span ファイルを選択 or ドロップ
          .caption.error--text(v-if="isNew && !files.length") ファイルは必須項目です

          v-text-field(v-model="input.name", label="名前")

          v-layout
            v-flex(xs6)
              v-btn(
                type="submit",
                :disabled="(isNew && !files.length) || states.saving || states.destroying",
                color="primary"
              ) 保存
            v-flex(xs6, v-if="!isNew").text-xs-right
              v-btn(
                type="button",
                :disabled="states.saving || states.destroying",
                @click="states.deletePostDialog = true",
                color="error"
              ) 削除

    Dialog(v-model="states.deletePostDialog", title="ファイル削除", :executing-state="states.destroying", @exec="destroy")
      div このファイルを削除してよろしいですか？

    LeaveDialog(:enable-cond="isInputUpdated")
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'
import VueUploadComponent from 'vue-upload-component'
import _ from 'lodash'
import Dialog from '@/components/Dialog'
import LeaveDialog from '@/components/LeaveDialog'

const API_URI = process.env.API_URI

export default {
  name: 'UploadForm',
  components: {
    LeaveDialog,
    VueUploadComponent,
    Dialog
  },
  props: {
    isNew: {
      type: Boolean,
      default: false,
      required: false
    }
  },
  data () {
    return {
      input: {},
      files: [],
      states: {
        deletePostDialog: false,
        saving: false,
        destroying: false
      },
      toRoute: null,
      apiUri: API_URI
    }
  },
  computed: {
    ...mapState('uploads', [
      'upload'
    ])
  },
  methods: {
    ...mapMutations('snackbar', {
      showSnackbar: 'show',
      closeSnackbar: 'close'
    }),
    ...mapMutations('progressbar', {
      startProgress: 'start',
      endProgress: 'end'
    }),
    ...mapMutations('posts', [
      'setPost'
    ]),
    ...mapActions('uploads', {
      showUpload: 'show',
      updateUpload: 'update',
      deleteUpload: 'delete'
    }),
    init () {
      this.input = _.cloneDeep(this.upload)
    },
    isInputUpdated () {
      return !_.isEqual(this.input, this.upload) || this.files.length
    },
    onFileChange (newFile, oldFile) {
      if (newFile && !oldFile) {
        // added
        this.input.name = newFile.name
        // thumb
        if (newFile.type.match(/^image/)) {
          const URL = window.URL || window.webkitURL
          if (URL && URL.createObjectURL) {
            newFile.thumb = URL.createObjectURL(newFile.file)
          }
        }
      } else if (newFile && oldFile && newFile.success !== oldFile.success) {
        // uploaded
        if (this.$refs.uploader.uploaded) {
          // all files have been uploaded
          this.onUploaded(newFile.response)
        }
      }
    },
    async save () {
      this.states.saving = true
      this.closeSnackbar()
      this.startProgress()

      if (this.files.length) {
        // 画像をアップロードする
        const file = this.files[0]
        const token = (`${document.cookie};`.match(/XSRF-TOKEN=(.+?);/) || [])[1]
        file.headers['X-XSRF-TOKEN'] = decodeURIComponent(token)
        file.data.name = this.input.name
        if (!this.isNew) {
          file.postAction = `${API_URI}/uploads/${this.upload.id}`
          file.data._method = 'PUT'
        }

        this.$refs.uploader.active = true // upload
      } else {
        // 画像をアップロードせずに更新
        await this.updateUpload(this.input)
        this.onUploaded()
      }
    },
    async onUploaded (response) {
      if (response) {
        await this.showUpload({id: response.id})
      }
      this.init()

      this.endProgress()
      this.showSnackbar('保存しました')
      this.states.saving = false

      this.$emit('saved')
    },
    async destroy () {
      this.states.destroying = true
      this.closeSnackbar()

      await this.deleteUpload({id: this.$route.params.id})

      this.showSnackbar('削除しました')
      this.states.destroying = false

      this.$router.push('/uploads')
    }
  },
  created () {
    this.init()
  }
}
</script>

<style scoped>
  .preview-area .thumbnail {
    max-width: 150px;
    max-height: 150px;
  }
  .preview-area .file {
    display: inline-block;
    border: 3px solid #ccc;
  }
  .preview-area .file >>> .v-icon {
    vertical-align: middle;
  }
</style>
