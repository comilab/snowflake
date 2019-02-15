<template lang="pug">
  div
    v-container
      v-card
        v-card-text
          v-form(@submit.prevent="search")
            v-text-field(
              type="search",
              v-model="query.keyword",
              label="キーワード検索",
              clearable
              append-outer-icon="search",
              @click:append-outer="search"
            )

    v-container.grid-container(grid-list-lg)
      v-layout(row, wrap)
        v-flex(v-for="page in pages", :key="page.id", xs12, sm6, xl3)
          v-card(:class="{ 'blue lighten-5': selected[page.id] }")
            v-card-title.pb-1
              .headline.text-truncate
                v-icon.mr-1(color="grey", v-if="!page.is_public") lock
                span {{ page.title }}
            v-card-text.py-1
              .grid-body-text {{ page.description }}
            v-card-actions
              v-spacer
              v-tooltip(top)
                v-btn(icon, :to="`/pages/${page.id}`", slot="activator")
                  v-icon edit
                span 編集

    .text-xs-center.pagination.mt-3
      v-pagination(v-model="query.page", :length="lastPage", @input="search")

    v-tooltip(left)
      v-btn(color="primary", to="/pages/new", fixed, bottom, right, fab, slot="activator")
        v-icon add
      span 新規ページ作成
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
      showGrid: true,
      selected: {},
      query: {
        page: 1,
        keyword: ''
      },
      states: {
        deleteDialog: false
      },
      loadings: {
        delete: false
      }
    }
  },
  computed: {
    ...mapState('pages', [
      'pages',
      'lastPage'
    ]),
    ...mapState('settings', [
      'settings'
    ]),
    selectedCount () {
      return _.filter(this.selected).length
    }
  },
  watch: {
    '$route': 'init'
  },
  methods: {
    ...mapActions('pages', [
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
    deletePosts () {
      //
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
  /*
  .toolbar {
    top: 64px;
  }
  @media only screen and (max-width: 959px) {
    .toolbar {
      top: 48px;
    }
    .list-container {
      padding: 0;
    }
  }
  @media only screen and (max-width: 599px) {
    .toolbar {
      top: 56px;
    }
  }
  */
  .grid-container .grid-body-text {
    max-height: 7.5rem;
    overflow: hidden;
  }
  .tag-link >>> .v-chip__content {
    cursor: pointer !important;
  }
  .pagination {
    margin-bottom: 6rem;
  }
</style>
