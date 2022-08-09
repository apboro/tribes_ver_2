<template>
  <div>
    <transition name="bounce">
      <div class='tab' v-show='isActive'>
          <slot></slot>
      </div>
    </transition>
  </div>

</template>

<script>
export default {
  name: "Tab",
  props: ['title', 'icon'],
  data() {
    return {
      isActive: true
    }
  }
}
</script>

<style scoped>

</style>