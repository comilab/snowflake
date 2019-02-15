<template lang="pug">
  div
    template(v-if="allowNewItem")
      v-combobox(
        v-model="selected",
        :hide-no-data="!search",
        :items="items",
        :search-input.sync="search",
        hide-selected,
        :loading="states.loading",
        :label="label",
        multiple,
        small-chips,
        deletable-chips,
        :clearable="clearable"
      )
        template(slot="no-data")
          v-list-tile
            kbd Enter
            span で新しいタグを追加:
            v-chip(small) {{ search }}
    template(v-else)
      v-autocomplete(
        v-model="selected",
        :hide-no-data="!search",
        :items="items",
        :search-input.sync="search",
        hide-selected,
        :loading="states.loading",
        :label="label",
        multiple,
        small-chips,
        deletable-chips,
        :clearable="clearable"
      )
        template(slot="no-data")
          v-list-tile 該当するタグがありません
</template>

<script>
import { mapState, mapActions } from 'vuex'
import _ from 'lodash'

export default {
  name: 'TagInput',
  props: {
    value: {
      type: Array,
      required: true
    },
    allowNewItem: {
      type: Boolean,
      default: false,
      required: false
    },
    label: {
      type: String,
      default: 'タグ',
      required: false
    },
    clearable: {
      type: Boolean,
      default: false,
      required: false
    }
  },
  data () {
    return {
      states: {
        loading: false
      },
      items: [],
      search: null
    }
  },
  computed: {
    ...mapState('tags', [
      'tags'
    ]),
    selected: {
      get () {
        return this.value
      },
      set (value) {
        if (this.value !== value) {
          this.$emit('input', value)
        }
      }
    }
  },
  watch: {
    async search (value) {
      if (!value) {
        return
      }
      if (this.states.loading) {
        return
      }

      this.states.loading = true

      await this.index({query: value})
      this.items = _.map(this.tags, 'tag_name')

      this.states.loading = false
    }
  },
  methods: {
    ...mapActions('tags', [
      'index'
    ])
  }
}
</script>
