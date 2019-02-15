<template lang="pug">
  v-card
    v-card-text
      v-form(ref="form", @submit.prevent="execSave")
        v-text-field(v-model="input.title", label="タイトル")

        v-textarea(ref="body", v-model="input.body", label="本文")
        .text-xs-right
          v-btn(color="info", @click="states.fileDialog = true")
            v-icon add
            span ファイルを挿入
          v-btn(color="info", @click="preview")
            v-icon.mr-1 visibility
            span プレビュー

        v-text-field(
          v-model="input.slug",
          label="ページURL",
          data-vv-name="slug",
          data-vv-as="ページURL",
          v-validate="'uniqueSlug'",
          :error-messages="errors.collect('slug')"
        )

        v-text-field(type="datetime-local", v-model="input.created_at", label="作成日", :required="!isNew")

        v-checkbox(v-model="input.is_public", label="記事を公開する")

        v-layout
          v-flex(xs6)
            v-btn(type="submit", :disabled="saving || destroying", color="primary") 保存
          v-flex(xs6, v-if="!isNew").text-xs-right
            v-btn(type="button", :disabled="saving || destroying", color="error", @click="states.deletePostDialog = true") 削除

    Dialog(v-model="states.deletePostDialog", title="ページ削除", :executing-state="destroying", @exec="destroy")
      div このページを削除してよろしいですか？

    LeaveDialog(:enable-cond="isInputUpdated")

    PostFormFile(v-model="selectedFileCode", :trigger.sync="states.fileDialog", @input="onFileSelected")

    v-dialog(v-model="states.previewDialog", full-width, scrollable)
      v-card.preview-dialog-card
        v-card-title.white--text.primary
          .title プレビュー
          v-spacer
          v-btn.ma-0(icon, dark, @click="states.previewDialog = false")
            v-icon close
        v-card-text.preview-body
          .text-xs-center(v-if="states.parsing")
            v-progress-circular(indeterminate, color="grey")
          div(v-else, v-html="parsedText")
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'
import { Validator } from 'vee-validate'
import _ from 'lodash'
import Dialog from '@/components/Dialog'
import PostFormFile from '@/components/PostFormFile'
import TagInput from '@/components/TagInput'
import LeaveDialog from '@/components/LeaveDialog'

export default {
  name: 'PageForm',
  components: {
    LeaveDialog,
    TagInput,
    PostFormFile,
    Dialog
  },
  props: [ 'isNew', 'save' ],
  data () {
    return {
      input: {},
      selectedFileCode: '',
      states: {
        deletePostDialog: false,
        fileDialog: false,
        previewDialog: false,
        searchingFile: false,
        parsing: false
      },
      saving: false,
      destroying: false,
      toRoute: null
    }
  },
  computed: {
    ...mapState('pages', [
      'page',
      'pages'
    ]),
    ...mapState('uploads', [
      'uploads'
    ]),
    ...mapState('posts', [
      'parsedText'
    ])
  },
  methods: {
    ...mapMutations('snackbar', {
      showSnackbar: 'show',
      closeSnackbar: 'close'
    }),
    ...mapMutations('pages', [
      'setPage'
    ]),
    ...mapActions('pages', {
      searchPage: 'index',
      deletePost: 'delete'
    }),
    ...mapActions('uploads', {
      searchUpload: 'index'
    }),
    ...mapActions('posts', {
      parseBody: 'parse'
    }),
    init () {
      this.input = _.cloneDeep(this.page)
    },
    isInputUpdated () {
      return !_.isEqual(this.input, this.page)
    },
    async execSave () {
      const isValid = await this.$validator.validateAll()
      if (!isValid) {
        return
      }

      this.saving = true
      this.closeSnackbar()

      await this.save(this.input)
      this.init()

      this.showSnackbar('保存しました')
      this.saving = false

      this.$emit('saved')
    },
    async destroy () {
      this.destroying = true
      this.closeSnackbar()

      await this.deletePost({id: this.$route.params.id})

      this.showSnackbar('削除しました')
      this.destroying = false

      this.$router.push('/pages')
    },
    async preview () {
      this.states.parsing = true
      this.states.previewDialog = true

      await this.parseBody(this.input.body)

      this.states.parsing = false
    },
    onFileSelected () {
      const cursorPos = this.$refs.body.$refs.input.selectionStart
      const beforeBody = this.input.body.substr(0, cursorPos)
      const afterBody = this.input.body.substr(cursorPos, this.input.body.length)

      this.input.body = `${beforeBody}${this.selectedFileCode}${afterBody}`
    }
  },
  created () {
    this.init()

    Validator.extend('uniqueSlug', {
      validate: async value => {
        const params = { slug: value }
        if (!this.isNew) {
          params.exclude_id = this.page.id
        }
        await this.searchPage(params)
        return !this.pages.length
      },
      getMessage: field => '既に使用されています'
    })
  }
}
</script>

<style scoped>
  .preview-dialog-card {
    width: 100%;
  }
  .preview-body {
    overflow-x: auto;
  }
</style>
