<template>
  <div id="app">
    <Navbar @change-view="handleChangeView" />
    <div class="">
      <div v-if="currentView === 'rooms'">
        <Rooms />
      </div>
      <div v-if="currentView === 'guests'">
        <Guests />
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from "./components/Navbar.vue";
import Rooms from "./components/RoomList.vue";
import Guests from "./components/GuestList.vue";

export default {
  name: "App",
  components: {
    Navbar,
    Rooms,
    Guests,
  },
  data() {
    return {
      currentView: "rooms",
    };
  },
  methods: {
    handleChangeView(view) {
      this.currentView = view;
    },
  },
};
</script>

<style>
.container {
  display: flex;
  justify-content: space-between;
}
</style>
