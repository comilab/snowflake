<template lang="pug">
  v-card
    v-card-text
      v-form(ref="form", @submit.prevent="execSave")
        v-text-field(v-model="input.title", label="タイトル")

        v-textarea(ref="body", v-model="input.body", label="本文", hint="markdown記法を使用できます", persistent-hint)
        .text-xs-right
          v-btn(color="info", @click="addMore")
            v-icon add
            span 「続きを読む」を挿入
          v-btn(color="info", @click="states.fileDialog = true")
            v-icon add
            span ファイルを挿入
          v-btn(color="info", @click="preview")
            v-icon.mr-1 visibility
            span プレビュー

        TagInput.mt-4(v-model="input.tags", allow-new-item)

        v-text-field(type="datetime-local", v-model="input.created_at", label="投稿日", :required="!isNew")

        v-checkbox(v-model="input.is_public", label="記事を公開する")

        v-layout
          v-flex(xs6)
            v-btn(type="submit", :disabled="states.saving || states.destroying", color="primary") 保存
          v-flex(xs6, v-if="!isNew").text-xs-right
            v-btn(type="button", :disabled="states.saving || states.destroying", color="error", @click="states.deletePostDialog = true") 削除

    Dialog(v-model="states.deletePostDialog", title="エントリ削除", :executing-state="states.destroying", @exec="destroy")
      div このエントリを削除してよろしいですか？

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
import _ from 'lodash'
import Dialog from '@/components/Dialog'
import PostFormFile from '@/components/PostFormFile'
import TagInput from '@/components/TagInput'
import LeaveDialog from '@/components/LeaveDialog'

export default {
  name: 'PostForm',
  components: {
    TagInput,
    PostFormFile,
    Dialog,
    LeaveDialog
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
        parsing: false,
        saving: false,
        destroying: false
      }
    }
  },
  computed: {
    ...mapState('posts', [
      'post',
      'parsedText'
    ]),
    ...mapState('uploads', [
      'uploads'
    ])
  },
  methods: {
    ...mapMutations('snackbar', {
      showSnackbar: 'show',
      closeSnackbar: 'close'
    }),
    ...mapMutations('posts', [
      'setPost'
    ]),
    ...mapActions('posts', {
      deletePost: 'delete',
      parseBody: 'parse'
    }),
    ...mapActions('uploads', {
      searchUpload: 'index'
    }),
    init () {
      this.input = _.cloneDeep(this.post)
    },
    isInputUpdated () {
      return !_.isEqual(this.input, this.post)
    },
    async execSave () {
      this.states.saving = true
      this.closeSnackbar()

      await this.save(this.input)
      this.init()

      this.showSnackbar('保存しました')
      this.states.saving = false

      this.$emit('saved')
    },
    async destroy () {
      this.states.destroying = true
      this.closeSnackbar()

      await this.deletePost({id: this.$route.params.id})

      this.showSnackbar('削除しました')
      this.states.destroying = false

      this.$router.push('/posts')
    },
    async preview () {
      this.states.parsing = true
      this.states.previewDialog = true

      await this.parseBody(this.input.body)

      this.states.parsing = false
    },
    addMore () {
      const cursorPos = this.$refs.body.$refs.input.selectionStart
      const beforeBody = this.input.body.substr(0, cursorPos)
      const afterBody = this.input.body.substr(cursorPos, this.input.body.length)

      this.input.body = `${beforeBody}<!-- more -->${afterBody}`
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
