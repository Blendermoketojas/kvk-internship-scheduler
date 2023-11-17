<template>
  <Sidebar style=" z-index:1000;">
</Sidebar>
    <DxScheduler   id="scheduler"  :adaptivity-enabled="true" :views="views" current-view="week" >

    </DxScheduler>

  </template>
  
  <script>
  import 'devextreme/dist/css/dx.light.css'; 
  import { DxScheduler} from 'devextreme-vue/scheduler';

  import Sidebar from "@/components/Sidebar.vue";

  export default {
   
    components: {
        DxScheduler,
        Sidebar,
    },
    name: 'CalendarComponent',
    data() {
      return {
        views: ['month','week'],
      };
    },
    methods: {

    },
  };
  </script>
<style scoped>
#scheduler {
    height: 600px;
}
</style>
