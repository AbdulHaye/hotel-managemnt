<template>
  <div id="rooms">
    <div v-if="successMessage" class="alert alert-success" role="alert">
      {{ successMessage }}
    </div>

    <div class="d-flex justify-content-start container mt-5 mb-5">
      <h2 class="room_head">Rooms</h2>
      <button class="btn_room" @click="showAddRoomForm = !showAddRoomForm">
        {{ showAddRoomForm ? "Cancel" : "Add New Room" }}
      </button>
    </div>

    <!-- Form for adding a new room -->
    <div v-if="showAddRoomForm" class="add-room-form">
      <h3>Add New Room</h3>
      <form @submit.prevent="addRoom">
        <label for="floor_number">Floor Number:</label>
        <input v-model="newRoom.floor_number" type="number" required />

        <label for="room_number">Room Number:</label>
        <input v-model="newRoom.room_number" type="number" required />

        <label for="capacity">Capacity:</label>
        <input v-model="newRoom.capacity" type="number" required />

        <label for="status">Status:</label>
        <select v-model="newRoom.status" required>
          <option value="READY">READY</option>
          <option value="TAKEN">TAKEN</option>
          <option value="MAINTENANCE">MAINTENANCE</option>
        </select>

        <button type="submit">Add Room</button>
      </form>
    </div>

    <div v-if="selectedRoom && showAssignGuestForm" class="assign-guest-form">
      <h3>Assign Guest to Room</h3>
      <label for="guest">Select Guest:</label>
      <select v-model="selectedGuestId">
        <option v-for="guest in guests" :key="guest.id" :value="guest.id">
          {{ guest.full_name }}
        </option>
      </select>
      <button @click="assignRoom">Assign Room</button>
      <button @click="cancelAssign">Cancel</button>
    </div>

    <div class="rooms-container justify-content-start container">
      <div v-for="room in rooms" :key="room.room_number" class="room">
        <p class="room_box">Room # {{ room.room_number }}</p>
        <p class="room_capacity">Capacity: {{ room.capacity }}</p>
        <p class="room_status">Status: {{ room.status }}</p>
        <p v-if="room.assignor" class="room_assignor">
          Assigned by: {{ room.assignor }}
        </p>
        <div class="btn_all_box">
          <button
            v-if="room.status !== 'READY'"
            @click="setRoomReady(room)"
            class="btn_room_box"
          >
            Set as Ready
          </button>
          <button
            v-if="room.status === 'TAKEN'"
            @click="deassignRoom(room)"
            class="btn_room_box"
          >
            Deassign Room
          </button>
          <button
            v-if="room.status === 'READY'"
            @click="prepareAssignRoom(room)"
            class="btn_room_box"
          >
            Assign Room
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "RoomList",
  data() {
    return {
      showAddRoomForm: false,
      showAssignGuestForm: false,
      newRoom: {
        floor_number: "",
        room_number: "",
        capacity: "",
        status: "READY",
      },
      rooms: [],
      guests: [],
      selectedRoom: null,
      selectedGuestId: null,
      successMessage: "",
    };
  },
  async mounted() {
    this.fetchRooms();
    this.fetchGuests();
  },
  methods: {
    async fetchRooms() {
      try {
        const response = await axios.get("http://localhost:8000/api/rooms");
        this.rooms = response.data;
      } catch (error) {
        console.error("Error fetching rooms:", error);
      }
    },
    async fetchGuests() {
      try {
        const response = await axios.get("http://localhost:8000/api/guests");
        this.guests = response.data;
      } catch (error) {
        console.error("Error fetching guests:", error);
      }
    },
    async addRoom() {
      if (
        this.newRoom.floor_number &&
        this.newRoom.room_number &&
        this.newRoom.capacity &&
        this.newRoom.status
      ) {
        try {
          const response = await axios.post(
            "http://localhost:8000/api/rooms",
            this.newRoom
          );
          this.rooms.push(response.data);
          this.resetNewRoom();
          this.showAddRoomForm = false;
        } catch (error) {
          console.error("Error adding room:", error);
        }
      }
    },
    async setRoomReady(room) {
      try {
        const response = await axios.post(
          `http://localhost:8000/api/rooms/${room.id}/set-ready`
        );
        const index = this.rooms.findIndex((r) => r.id === room.id);
        if (index !== -1) {
          this.rooms[index] = response.data.room;
          this.successMessage = "Room status set to READY!";
        }
      } catch (error) {
        console.error("Error setting room as ready:", error);
      }
    },
    async assignRoom() {
      if (this.selectedRoom && this.selectedGuestId) {
        try {
          const response = await axios.post(
            `http://localhost:8000/api/rooms/${this.selectedRoom.id}/assign/${this.selectedGuestId}`
          );
          const index = this.rooms.findIndex(
            (r) => r.id === this.selectedRoom.id
          );
          if (index !== -1) {
            this.rooms.splice(index, 1, response.data.room);
            this.successMessage = "Room successfully assigned!";
          }
          this.cancelAssign();
        } catch (error) {
          console.error("Error assigning room:", error);
        }
      }
    },
    prepareAssignRoom(room) {
      this.selectedRoom = room;
      this.showAssignGuestForm = true;
    },
    cancelAssign() {
      this.showAssignGuestForm = false;
      this.selectedRoom = null;
      this.selectedGuestId = null;
    },
    async deassignRoom(room) {
      try {
        const response = await axios.post(
          `http://localhost:8000/api/rooms/${room.id}/deassign`
        );
        const index = this.rooms.findIndex((r) => r.id === room.id);
        if (index !== -1) {
          this.rooms[index] = response.data.room;
          this.successMessage = "Room successfully deassigned!";
        }
      } catch (error) {
        console.error("Error deassigning room:", error);
      }
    },
    resetNewRoom() {
      this.newRoom = {
        floor_number: "",
        room_number: "",
        capacity: "",
        status: "READY",
      };
    },
  },
};
</script>

<style src="./RoomList.css"></style>
