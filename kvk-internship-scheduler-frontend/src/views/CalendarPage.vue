<template>
  <header-nav />
  <DxScheduler
    id="scheduler"
    :adaptivity-enabled="true"
    :views="views"
    current-view="week"
    :data-source="dataSource"
    @appointmentAdded="showValue"
  >
  </DxScheduler>

</template>
<script>
import "devextreme/dist/css/dx.light.css";
import { DxScheduler } from "devextreme-vue/scheduler";
import HeaderNav from "@/components/DesktopHeader.vue";
  import { mapGetters } from 'vuex';

export default {
  components: {
    DxScheduler,
    HeaderNav,
  },
  name: "CalendarComponent",
  data() {
    return {
      views: ["month", "week"],
     dataSource:[],
 
   
    };
  },
  methods: {
    showValue(e) {
      const appointmentData = e.appointmentData;
      console.log(e);
      console.log(appointmentData);
  
    }
      updateCurrentInternship(internship) {
        this.$store.commit('setCurrentInternship', internship)
      },
      getActiveInternship() {
        // partraukimo logika this.updateCurrentIntership(response.data)
      }
    },
  },
};
</script>
    },
    computed: {
      ...mapGetters([
        'getCurrentInternship'
      ])
    },
    mounted() {
      if (!this.getCurrentInternship) {
        this.getActiveInternship();
      }
    }
  };
  </script>
<style scoped>
#scheduler {
  height: 600px;
}
</style>
