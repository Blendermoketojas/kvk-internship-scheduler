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
    apiClient.get('/profile').then(response => {
      this.$store.commit('setUser', response.data); 
      const userHeaderInfo = { fullname: response.data.fullname, image_path: response.data.image_path };
      localStorage.setItem('user', JSON.stringify(userHeaderInfo))
    });
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