<template>
  <div id="guests">
    <div class="d-flex justify-content-start container mt-5 mb-5">
      <h2 class="room_head">Guests</h2>
      <button class="btn_room" @click="showAddGuestForm = !showAddGuestForm">
        {{ showAddGuestForm ? "Cancel" : "Add New Guest" }}
      </button>
    </div>

    <div v-if="showAddGuestForm" class="add-guest-form form_back">
      <h3>Add New Guest</h3>
      <form @submit.prevent="addGuest" >
        <label for="full_name">Full Name:</label>
        <input v-model="newGuest.full_name" type="text" required />

        <label for="age">Age:</label>
        <input v-model="newGuest.age" type="number" required />

        <button type="submit">Add Guest</button>
      </form>
    </div>

    <div v-if="showEditGuestForm" class="edit-guest-form">
      <h3>Edit Guest</h3>
      <form @submit.prevent="updateGuest">
        <label for="edit_full_name">Full Name:</label>
        <input v-model="currentGuest.full_name" type="text" required />

        <label for="edit_age">Age:</label>
        <input v-model="currentGuest.age" type="number" required />

        <button type="submit">Update Guest</button>
        <button type="button" @click="cancelEdit">Cancel</button>
      </form>
    </div>

    <div class="guests-container justify-content-start container">
      <div v-for="guest in guests" :key="guest.id" class="guest">
        <p class="room_name_guest">{{ guest.full_name }}</p>
        <p class="room_num_guest">Age: {{ guest.age }}</p>
        <div class="guest-actions">
          <button class="btn_room_box" @click="editGuest(guest)">Edit</button>
          <button class="btn_room_box" @click="deleteGuest(guest)">
            Delete
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "GuestList",
  data() {
    return {
      showAddGuestForm: false,
      showEditGuestForm: false,
      newGuest: {
        full_name: "",
        age: "",
      },
      currentGuest: null,
      guests: [],
    };
  },
  async mounted() {
    this.fetchGuests();
  },
  methods: {
    async fetchGuests() {
      try {
        const response = await axios.get("http://localhost:8000/api/guests");
        this.guests = response.data;
      } catch (error) {
        console.error("Error fetching guests:", error);
      }
    },
    async addGuest() {
      if (this.newGuest.full_name && this.newGuest.age) {
        try {
          const response = await axios.post(
            "http://localhost:8000/api/guests",
            this.newGuest
          );
          this.guests.push(response.data); 
          this.resetNewGuest();
          this.showAddGuestForm = false;
        } catch (error) {
          console.error("Error adding guest:", error);
        }
      }
    },
    async updateGuest() {
      if (this.currentGuest) {
        try {
          const response = await axios.put(
            `http://localhost:8000/api/guests/${this.currentGuest.id}`,
            this.currentGuest
          );
          const index = this.guests.findIndex(
            (g) => g.id === this.currentGuest.id
          );
          if (index !== -1) {
            this.guests.splice(index, 1, response.data); 
          }
          this.cancelEdit();
        } catch (error) {
          console.error("Error updating guest:", error);
        }
      }
    },
    cancelEdit() {
      this.showEditGuestForm = false;
      this.currentGuest = null;
    },
    async deleteGuest(guest) {
      try {
        await axios.delete(`http://localhost:8000/api/guests/${guest.id}`);
        this.guests = this.guests.filter((g) => g.id !== guest.id);
      } catch (error) {
        console.error("Error deleting guest:", error);
      }
    },
    editGuest(guest) {
      this.currentGuest = { ...guest };
      this.showEditGuestForm = true;
    },
    resetNewGuest() {
      this.newGuest = {
        full_name: "",
        age: "",
      };
    },
  },
};
</script>

<style src="./GuestList.css"></style>
