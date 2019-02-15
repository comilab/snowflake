<template lang="pug">
  div
    v-dialog(v-model="innerTrigger", width="90%", scrollable)
      v-card
        v-card-title.primary
          .white--text.title ファイルを挿入
        v-card-text
          .text-xs-center(v-if="states.loading")
            v-progress-circular(indeterminate, color="grey")

          div(v-else)
            template(v-if="uploads.length")
              v-form(@submit.prevent="search")
                v-text-field.ma-0(
                  type="search",
                  v-model="query.keyword",
                  label="検索",
                  solo
                  append-icon="search",
                  @click:append="search"
                )
              v-list.elevation-1(two-line)
                template(v-for="(upload, index) in uploads")
                  v-list-tile(avatar)
                    v-list-tile-avatar
                      v-avatar(tile)
                        v-img(v-if="upload.public_thumb_path", :src="upload.public_thumb_path")
                        v-icon(v-else) insert_drive_file
                    v-list-tile-content
                      v-list-tile-title {{ upload.name }}
                      v-list-tile-sub-title {{ upload.filetype }}
                    v-list-tile-action
                      v-tooltip(left, v-if="upload.public_thumb_path")
                        v-btn(icon, slot="activator", @click="insertImage(upload)")
                          v-icon add_photo_alternate
                        span 画像を挿入
                      v-tooltip(left)
                        v-btn(icon, slot="activator", @click="insertCode(upload)")
                          v-icon link
                        span リンクを挿入
                  v-divider(v-if="index + 1 < uploads.length", :key="index")

              .text-xs-center.pagination.mt-3
                v-pagination(v-model="query.page", :length="lastPage", @input="search")

            template(v-else)
              v-card.elevation-1
                v-card-text ファイルがありません。

        v-divider
        v-card-actions
          v-btn(color="primary", @click="openNewDialog") 新規ファイル追加
          v-spacer
          v-btn(color="primary", flat, @click="innerTrigger = false") 閉じる

    v-dialog(v-model="states.newDialog", width="90%", scrollable)
      v-card
        v-card-title.primary
          .white--text.title 新規ファイル追加
        v-card-text
          UploadForm(v-if="states.newDialog", is-new, @saved="onUploaded")
        v-divider
        v-card-actions
          v-spacer
          v-btn(color="primary", flat, @click="states.newDialog = false") 閉じる
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'
import UploadForm from '@/components/UploadForm'

export default {
  name: 'PostFormFile',
  components: {
    UploadForm
  },
  props: {
    value: {
      type: String,
      required: true
    },
    trigger: {
      type: Boolean,
      required: true
    }
  },
  data () {
    return {
      query: {
        page: 1,
        keyword: ''
      },
      states: {
        loading: false,
        newDialog: false
      }
    }
  },
  computed: {
    ...mapState('uploads', [
      'upload',
      'uploads',
      'lastPage'
    ]),
    innerTrigger: {
      get () {
        return this.trigger
      },
      set (value) {
        if (this.trigger !== value) {
          this.$emit('update:trigger', value)
        }
      }
    }
  },
  watch: {
    trigger (value) {
      if (value) {
        this.search()
      }
    }
  },
  methods: {
    ...mapMutations('uploads', [
      'setUpload'
    ]),
    ...mapActions('uploads', [
      'index'
    ]),
    async search () {
      this.states.loading = true

      await this.index(this.query)

      this.states.loading = false
    },
    insertImage (upload) {
      const code = `![${upload.name}](${upload.public_path})`
      this.$emit('input', code)
      this.innerTrigger = false
    },
    insertCode (upload) {
      const code = `[${upload.name}](${upload.public_path})`
      this.$emit('input', code)
      this.innerTrigger = false
    },
    openNewDialog () {
      this.setUpload({
        name: ''
      })
      this.states.newDialog = true
    },
    onUploaded () {
      this.states.newDialog = false
      this.search()
    }
  }
}
</script>
