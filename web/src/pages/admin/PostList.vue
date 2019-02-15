<template lang="pug">
  div
    v-container
      v-card
        v-card-text
          v-form(@submit.prevent="search")
            TagInput(v-model="query.tags")
            v-text-field(
              type="search",
              v-model="query.keyword",
              label="キーワード",
              clearable
              append-outer-icon="search",
              @click:append-outer="search"
            )

    v-container.grid-container(grid-list-lg)
      v-layout(row, wrap)
        v-flex(v-for="post in posts", :key="post.id", xs12, sm6, xl3)
          v-card
            v-card-title.pb-1
              .headline.text-truncate
                v-icon.mr-1(color="grey", v-if="!post.is_public") lock
                span {{ post.title }}
            v-card-text.py-1
              template(v-if="post.images")
                v-layout(row, wrap)
                  v-flex(xs6)
                    v-img(:src="post.images[0].public_path", height="7.5rem", cover)
                  v-flex(xs6)
                    .grid-body-text {{ post.description }}
              template(v-else)
                .grid-body-text {{ post.description }}
              .mt-2
                v-chip(v-for="(tag, index) in post.tags", :key="index" small) {{ tag }}
            v-card-actions
              v-layout(align-center, justify-end)
                v-tooltip(top)
                  template(slot="activator", v-if="settings.like_enabled")
                    .grid-like.mx-3
                      v-icon favorite
                      span.ml-1.mr-2 {{ post.likes }}
                  span いいね
                v-tooltip(top)
                  template(slot="activator", v-if="settings.comment_type !== 'close'")
                    v-btn(flat, :to="`/posts/${post.id}/comments`")
                      v-icon comment
                      span.ml-1.mr-2 {{ post.comments_count }}
                  span コメント
                v-tooltip(top)
                  v-btn(flat, :to="`/posts/${post.id}`", slot="activator")
                    v-icon edit
                  span 編集

    .text-xs-center.pagination.mt-3
      v-pagination(v-model="query.page", :length="lastPage", @input="search")

    v-tooltip(left)
      v-btn(color="primary", to="/posts/new", fixed, bottom, right, fab, slot="activator")
        v-icon add
      span 新規エントリ作成
</template>

<script>
import { mapState, mapActions } from 'vuex'
import _ from 'lodash'
import Dialog from '@/components/Dialog'
import TagInput from '@/components/TagInput'

export default {
  name: 'PostList',
  components: {
    TagInput,
    Dialog
  },
  data () {
    return {
      query: {
        page: 1,
        keyword: '',
        tags: []
      }
    }
  },
  computed: {
    ...mapState('posts', [
      'posts',
      'lastPage'
    ]),
    ...mapState('settings', [
      'settings'
    ])
  },
  watch: {
    '$route': 'init'
  },
  methods: {
    ...mapActions('posts', [
      'index'
    ]),
    toggleStyle () {
      this.showGrid = !this.showGrid
    },
    search () {
      this.$router.push({
        query: this.query
      })
    },
    async init () {
      this.query = _.assign({
        page: 1,
        keyword: '',
        tags: []
      }, this.$route.query)
      if (!_.isArray(this.query.tags)) {
        this.query.tags = [ this.query.tags ]
      }

      await this.index(this.query)
    }
  },
  created () {
    this.init()
  }
}
</script>

<style scoped>
  .grid-container .grid-body-text {
    max-height: 7.5rem;
    overflow: hidden;
  }
  .grid-like i, .grid-like span {
    vertical-align: middle;
  }
  .tag-link >>> .v-chip__content {
    cursor: pointer !important;
  }
  .pagination {
    margin-bottom: 6rem;
  }
</style>
