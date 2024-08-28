<template>
    <nav>
      <ul class="navbar">
        <li class="navbar-item ">
          <a @click="changeView('rooms')" class="navbar_heading">Rooms</a>
        </li>
        <li class="navbar-item">
          <a @click="changeView('guests')" class="navbar_heading">Guests</a>
        </li>
      </ul>
    </nav>
  </template>
  
  <script>
  export default {
    name: 'Navbar',
    methods: {
      changeView(view) {
        this.$emit('change-view', view);
      }
    }
  }
  </script>
  
 <style src="./Navbar.css"/>
  