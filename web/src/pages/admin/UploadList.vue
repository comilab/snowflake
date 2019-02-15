<template lang="pug">
  div
    v-container.mb-0.pb-0
      v-card
        v-card-text
          v-form(@submit.prevent="search")
            v-text-field(
              type="search",
              v-model="query.keyword",
              label="検索",
              append-outer-icon="search",
              @click:append-outer="search"
            )

    v-container.list-container
      template(v-if="uploads.length")
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
                v-tooltip(top)
                  v-btn(icon, :href="upload.public_path", target="_blank", slot="activator")
                    v-icon cloud_download
                  span ファイルを表示
              v-list-tile-action
                v-tooltip(top)
                  v-btn(icon, :to="`/uploads/${upload.id}`", slot="activator")
                    v-icon edit
                  span 編集
            v-divider(v-if="index + 1 < uploads.length", :key="index")

        .text-xs-center.pagination.mt-3
          v-pagination(v-model="query.page", :length="lastPage", @input="search")

      template(v-else)
        v-card.elevation-1
          v-card-text ファイルがありません

    v-tooltip(left)
      v-btn(color="primary", to="/uploads/new", fixed, bottom, right, fab, slot="activator")
        v-icon add
      span 新規ファイル登録
</template>

<script>
import { mapState, mapActions } from 'vuex'
import _ from 'lodash'
import Dialog from '@/components/Dialog'

export default {
  name: 'UploadList',
  components: {
    Dialog
  },
  data () {
    return {
      query: {
        page: 1,
        keyword: ''
      }
    }
  },
  computed: {
    ...mapState('uploads', [
      'uploads',
      'lastPage'
    ])
  },
  watch: {
    '$route': 'init'
  },
  methods: {
    ...mapActions('uploads', [
      'index'
    ]),
    search () {
      this.$router.push({
        query: this.query
      })
    },
    async init () {
      this.query = _.assign({
        page: 1,
        keyword: ''
      }, this.$route.query)

      await this.index(this.query)
    }
  },
  created () {
    this.init()
  }
}
</script>

<style scoped>
  @media only screen and (max-width: 959px) {
    .list-container {
      padding: 0;
    }
  }
  .pagination {
    margin-bottom: 6rem;
  }
</style>
