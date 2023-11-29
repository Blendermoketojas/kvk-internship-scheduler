<template>
  <router-view />

</template>

<script>
import { mapGetters } from 'vuex';
import apiClient from './utils/api-client';

export default {
  computed: {
    ...mapGetters([
      'getUser'
    ])
  },
  mounted() {
  }
}
</script>

<style>
.styleless-button {
  background: none;
  color: inherit;
  border: none;
  padding: 0;
  margin: 0;
  font: inherit;
  cursor: pointer;
  outline: inherit;
}

.styleless-button:focus,
.styleless-button:active {
  outline: none;
}
</style>