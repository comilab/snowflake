<template lang="pug">
  v-dialog(v-model="trigger", width="500")
    v-card
      v-card-title.white--text.title(:class="headerClass", v-if="title") {{ title }}
      v-card-text
        slot
      v-divider
      v-card-actions
        v-spacer
        v-btn(:color="color", @click="$emit('exec')", :disabled="executingState", :loading="executingState") OK
        v-btn(:color="color", flat, @click="trigger = false", :disabled="executingState") キャンセル
</template>

<script>
export default {
  name: 'Dialog',
  props: {
    title: {
      type: String,
      default: null,
      required: false
    },
    value: {
      type: Boolean,
      default: false,
      required: true
    },
    executingState: {
      type: Boolean,
      default: false,
      required: false
    },
    color: {
      type: String,
      default: 'primary',
      required: false
    }
  },
  computed: {
    trigger: {
      get () {
        return this.value
      },
      set (value) {
        if (this.value !== value) {
          this.$emit('input', value)
        }
      }
    },
    headerClass () {
      const result = {}
      result[this.color] = true
      return result
    }
  }
}
</script>
