<template>
    <div class="main-content-container">
        <slot></slot>
    </div>
</template>

<script>
import customHeader from "../DesktopHeader.vue";
export default {
    components: {
    customHeader,

  },

}
</script>

<style scoped>
.main-content-container {
    padding: 0px 200px;
}
</style>