<template>
  <div class="d-flex justify-content-between">
    {{ value }}
    <button class="btn btn-xs word-keep" @click="search_downward(row.id)">{{ title }}</button>
  </div>
</template>
<script>
export default {
  props: ['value', 'row', 'query', 'trans', 'xprops'],
  data() {
    return {
      title: trans.field.button_downward
    }
  },
  mounted: function(){
  },
  methods: {
    search_downward(id) {
      this.xprops.eventbus.$emit('RELOAD_CURRENT', id);
    }
  }
}
</script>
