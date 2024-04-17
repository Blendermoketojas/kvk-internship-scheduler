<template>
  <custom-header v-if="isDesktop" />
  <mobile-nav v-if="!isDesktop" />
  <div class="body-div">
    <div class="main-div">
      <v-dialog v-model="showAddMemberModal" persistent max-width="550px">
        <v-card>
          <v-card-title>Pasirinkite norimą naudotoją</v-card-title>
          <v-card-text>
            <user-search @send-student-id="handleNewGroupMember"></user-search>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn text @click="showAddMemberModal = false">Atšaukti</v-btn>
            <v-btn color="primary" @click="addMemberToGroup">Pridėti</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-dialog v-model="dialog" persistent max-width="300px">
        <v-card>
          <v-card-title class="text-h5">Žinutės ištrynimas</v-card-title>
          <v-card-text>Ar tikrai norite ištrinti šią žinutę?</v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="dialog = false"
              >Atšaukti</v-btn
            >
            <v-btn color="blue darken-1" text @click="confirmDelete"
              >Ištrinti</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-dialog v-model="newChatModal" max-width="500px">
        <v-card>
          <v-card-title>Naujas pokalbis</v-card-title>
          <v-card-text>
            <v-text-field
              v-model="searchTerm"
              label="Įveskite naudotojo vardą"
              @input="debouncedSearchUsers"
              outlined
              dense
              clearable
            ></v-text-field>
            <v-list>
              <v-list-item
                v-for="user in searchResults"
                :key="user.id"
                @click="selectUser(user)"
                class="search-result-item"
              >
                <v-list-item-text>
                  <v-list-item-title>{{ user.fullname }}</v-list-item-title>
                </v-list-item-text>
              </v-list-item>
            </v-list>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn text @click="newChatModal = false">Atšaukti</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-dialog v-model="newGroupModal" max-width="600px">
        <v-card>
          <v-card-title>Sukurti naują grupę</v-card-title>
          <v-card-text>
            <v-text-field
              v-model="groupInfo.name"
              label="Grupės pavadinimas"
              outlined
              dense
              clearable
            ></v-text-field>

            <user-search @send-student-id="handleSelectedStudent"></user-search>
            <group-search
              @update:selectedGroupId="handleSelectedGroupId"
            ></group-search>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn text @click="newGroupModal = false">Atšaukti</v-btn>
            <v-btn color="primary" @click="createGroup">Sukurti</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <div class="pageDescription">
        <h1>Pokalbiai</h1>
        <h2>Čia galite susisiekti su kitu naudotoju</h2>
      </div>
      <div class="contentDiv">
        <div class="contact">
          <v-expansion-panels variant="accordion">
            <v-expansion-panel
              style="background-color: #f8f9fa"
              title="Asmeninės žinutės"
              @click="fetchConversations"
            >
              <v-expansion-panel-text id="contact-info">
                <v-infinite-scroll :height="300"  :loading="loadingMessages" @load="load">
                  <v-btn
                    v-for="participant in otherParticipants"
                    :key="participant.conversation_id"
                    class="chat-selection text-left"
                    :id="`btn-${participant.conversation_id}`"
                    @click="selectParticipant(participant)"
                  >
                    <img
                      :src="getImagePath(participant.image_path)"
                      alt="Profile Picture"
                      class="profile-picture"
                    />
                    {{ participant.fullname }}
                  </v-btn>
      
                </v-infinite-scroll>
                <v-btn
                  @click="newChatModal = true"
                  style="width: 100%; background-color: #cfcfcf"
                  >Naujas pokalbis</v-btn
                >
              </v-expansion-panel-text>
            </v-expansion-panel>
            <v-expansion-panel
              style="background-color: #f8f9fa"
              title="Grupės"
              @click="fetchGroupConversations"
            >
              <v-expansion-panel-text>
                <v-infinite-scroll  :loading="loadingMessages" @load="load" :height="300">
                  <v-btn
                    v-for="group in groups"
                    :key="group.conversation_id"
                    class="group-button"
                    @click="selectGroup(group.conversation_id)"
                  >
                    {{ group.group_name }}
                  </v-btn>
                </v-infinite-scroll>
                <v-btn
                  @click="newGroupModal = true"
                  style="width: 100%; background-color: #cfcfcf"
                  >Nauja grupė</v-btn
                >
              </v-expansion-panel-text>
            </v-expansion-panel>
          </v-expansion-panels>
        </div>
        <div class="chat-room">
          <v-progress-circular
            v-if="loadingMessages"
            indeterminate
            color="primary"
          ></v-progress-circular>
          <div class="chat-name-div">
            <div class="chat-name" v-if="currentParticipant">
              <img
                v-if="currentParticipant.image_path"
                :src="getImagePath(currentParticipant.image_path)"
                alt="Profile Picture"
                class="profile-picture"
              />
              <h3>
                {{ currentParticipant ? currentParticipant.fullname : "" }}
              </h3>
            </div>

            <v-btn
              v-if="currentParticipant && currentParticipant.type === 'group'"
              density="compact"
              icon="mdi-plus"
              @click="showAddMemberModal = true"
            ></v-btn>
          </div>

          <div
            class="messages-container"
            ref="messagesContainer"
            @scroll="handleScroll"
          >
            <div
              v-for="message in currentMessages"
              :key="message.id"
              :class="['message', message.isOutgoing ? 'outgoing' : 'incoming']"
            >
              <div class="message-sender">
                {{
                  message.user_profile
                    ? message.user_profile.fullname
                    : "Unknown"
                }}
              </div>
              {{ message.text }}
              <v-icon
                v-if="message.isOutgoing"
                @click.stop="deleteMessage(message.id)"
                class="delete-icon"
              >
                mdi-trash-can
              </v-icon>
              <div class="timestamp">{{ message.timestamp }}</div>
            </div>
          </div>

          <div class="message-input" v-if="currentParticipant || selectedUser">
            <v-text-field
              v-model="message"
              :placeholder="
                currentParticipant
                  ? 'Rašykite žinutę...'
                  : `Pradėkite pokalbį su ${
                      selectedUser ? selectedUser.fullname : ''
                    }`
              "
              single-line
              dense
              append-icon="mdi-send"
              @keyup.enter="sendMessage"
              @click:append="sendMessage"
              :disabled="sendingMessage"
            ></v-text-field>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import mobileNav from "@/components/MobileSidebar.vue";
import customHeader from "@/components/DesktopHeader.vue";
import apiClient from "@/utils/api-client";
import groupSearch from "@/components/GroupSearch.vue";
import { mapGetters } from "vuex";
import userSearch from "@/components/SearchUserProfile.vue";
import { debounce } from "lodash";

export default {
  components: { customHeader, mobileNav, userSearch, groupSearch },

  data() {
    return {
      showAddMemberModal: false,
      groups: [],
      newGroupModal: false,
      groupInfo: {
        name: "",
        selectedUsers: [],
      },
      searchResults: [],
      newChatModal: false,
      selectedUser: null,
      message: "",
      searchTimer: null,
      isDesktop: window.innerWidth > 950,
      otherParticipants: [],
      currentParticipant: null,
      currentMessages: [],
      dialog: false,
      selectedMessageId: null,
      loadingMessages: false,
      messagesOffset: 0,
      limit: 10,
      allMessagesLoaded: false,
      shouldScrollToBottom: true,
      newConversationPayload: null,
      sendingMessage: false,
      selectedUserIdToAdd: null,
      totalCount: 0,
    };
  },

  methods: {
    debouncedSearchUsers: debounce(function () {
      this.searchUsers();
    }, 300),

    addMember() {
    console.log("Add member functionality goes here.");
    this.showAddMemberModal = false; 
  },

  load({ done }) {
  // Start by setting loadingMessages to true when loading begins
  this.loadingMessages = true;

  // If the current offset is greater than or equal to totalCount, we're done
  if (this.messagesOffset >= this.totalCount) {
    done('empty');
    this.loadingMessages = false; // End loading
    return;
  }

  apiClient.get("/getUsersConversations", {
    params: {
      offset: this.messagesOffset,
      limit: this.limit
    }
  })
  .then(response => {
    // If we receive data and it's an array with length
    if (response.data.success && response.data.otherParticipants.length > 0) {
      // Push the new data into the array
      this.otherParticipants.push(...response.data.otherParticipants);
      // Update offset and totalCount
      this.messagesOffset += response.data.otherParticipants.length;
      this.totalCount = response.data.totalCount;
      // Successfully done
      done('ok');
    } else {
      // No new data means we've loaded everything
      this.allMessagesLoaded = true;
      done('empty');
    }
  })
  .catch(error => {
    // Handle any errors
    console.error("Error fetching conversations:", error);
    done('error');
  })
  .finally(() => {
    // Whether successful or not, we're no longer loading
    this.loadingMessages = false;
  });
},

  handleNewGroupMember(selectedUserId) {
      this.selectedUserIdToAdd = selectedUserId;
    },
    addMemberToGroup() {
      if (!this.currentParticipant || !this.currentParticipant.conversation_id || !this.selectedUserIdToAdd) {
        console.error("No current conversation selected or no user selected.");
        return;
      }
      if (!this.selectedUserIdToAdd) {
    console.error("No user selected to add.");
    return;
  }

      const payload = {
        conversation_id: this.currentParticipant.conversation_id,
        user_id: this.selectedUserIdToAdd,
      };

      apiClient.post('/add-user-to-group', payload)
        .then(response => {
          console.log("User added to group successfully", response);
          this.selectedUserIdToAdd = null;
          this.showAddMemberModal = false;
        })
        .catch(error => {
          console.error("Failed to add user to group", error);
          this.selectedUserIdToAdd = null;
        });
    },

    selectGroup(conversationId) {
      const selectedGroup = this.groups.find(
        (group) => group.conversation_id === conversationId
      );

      this.currentParticipant = {
        conversation_id: conversationId,
        fullname: selectedGroup.group_name,
        type: "group",
        image_path: "",
      };

      this.currentMessages = [];
      this.messagesOffset = 0;
      this.allMessagesLoaded = false;
      this.fetchConversationMessages(true);
    },

    fetchGroupConversations() {
      apiClient
        .get("/getGroupConversations")
        .then((response) => {
          const groupedByConversation = {};
          response.data.groupParticipants.forEach((participant) => {
            if (!groupedByConversation[participant.conversation_id]) {
              groupedByConversation[participant.conversation_id] = {
                group_name: participant.group_name,
                participants: [],
                conversation_id: participant.conversation_id,
              };
            }
            groupedByConversation[
              participant.conversation_id
            ].participants.push({
              fullname: participant.fullname,
              image_path: participant.image_path,
            });
          });

          this.groups = Object.values(groupedByConversation);
        })
        .catch((error) => {
          console.error("Error fetching group conversations:", error);
        });
    },

    processGroupConversations(participants) {
      const groups = {};

      participants.forEach((participant) => {
        if (!groups[participant.conversation_id]) {
          groups[participant.conversation_id] = {
            conversation_id: participant.conversation_id,
            participants: [],
          };
        }
        groups[participant.conversation_id].participants.push({
          fullname: participant.fullname,
          image_path: participant.image_path,
        });
      });

      return Object.values(groups);
    },

    handleSelectedStudent(selectedIds) {
      this.groupInfo.selectedUsers = selectedIds;
    },
    handleSelectedGroupId(groupId) {
      // this.selectedGroupId = groupId;

      apiClient
        .post(`/get-group-users-profile`, { groupId: groupId })
        .then((response) => {
          const userIds = response.data.map((user) => user.id);
          console.log(userIds);
          this.groupInfo.selectedUsers = userIds;
        })
        .catch((error) => {
          console.error("Error fetching group users:", error);
          this.selectedGroupId = null;
        });
    },

    createGroup() {
      const payload = {
        name: this.groupInfo.name,
        type: "group",
        userProfileId: this.groupInfo.selectedUsers,
      };

      apiClient
        .post("/conversations", payload)
        .then((response) => {
          this.groupInfo.name = "";
          this.groupInfo.selectedUsers = [];
          this.newGroupModal = false;
          this.currentParticipant = {
            conversation_id: response.data.conversation.id,
            fullname: response.data.conversation.name,
            image_path: "",
          };
          this.currentMessages = [];
          this.messagesOffset = 0;
          this.allMessagesLoaded = false;
          this.fetchConversationMessages(true);
        })
        .catch((error) => {
          console.error("Error creating new group:", error);
        });
    },

    searchUsers() {
      this.searchResults = [];

      if (this.searchTerm.trim()) {
        apiClient
          .post("/search-profiles", { fullName: this.searchTerm.trim() })
          .then((response) => {
            this.searchResults = response.data;
          })
          .catch((error) => {
            console.error("Error searching users:", error);
          });
      }
    },
    selectUser(user) {
      const existingConversation = this.findExistingConversationWithUser(
        user.id
      );

      if (existingConversation) {
        this.selectConversation(existingConversation);
      } else {
        if (user) {
          this.setupNewConversation(user);
          const payload = {
            user_id: user.id,
            fullname: user.fullname,
          };
          this.newConversationPayload = payload;
        } else {
          console.log("User object is null or undefined.");
        }
      }
      // Close the modal after handling the user selection
      this.newChatModal = false;
    },
    findExistingConversationWithUser(userId) {
      return this.otherParticipants.find(
        (participant) => participant.user_id === userId
      );
    },

    selectConversation(conversation) {
      this.currentParticipant = conversation;
      this.currentMessages = [];

      this.fetchConversationMessages(true);
    },

    //clears the messages of older conversations
    setupNewConversation(user) {
      this.currentParticipant = {
        fullname: user.fullname,
        image_path: user.image_path,
        conversation_id: null,
      };
      this.currentMessages = [];
    },

    selectParticipant(participant) {
      this.currentParticipant = participant;
      this.messagesOffset = 0;
      this.allMessagesLoaded = false;
      this.currentMessages = [];
      this.fetchConversationMessages(true);
    },

    handleScroll(event) {
      clearTimeout(this.throttleTimer);
      this.throttleTimer = setTimeout(() => {
        const container = this.$refs.messagesContainer;
        if (
          container.scrollTop < 50 &&
          !this.allMessagesLoaded &&
          !this.loadingMessages
        ) {
          this.fetchConversationMessages(false);
        }
        const isAtBottom =
          container.scrollHeight - container.scrollTop ===
          container.clientHeight;
        this.shouldScrollToBottom = isAtBottom;
      }, 200);
    },

    async sendMessage() {
      if (!this.message.trim()) {
        console.error("No message to send.");
        return;
      }
      this.sendingMessage = true;

      if (this.newConversationPayload) {
        let newPayload = {
          name:
            this.currentUserFullName +
            ", " +
            this.newConversationPayload.fullname,
          type: "private",
          userProfileId: [this.newConversationPayload.user_id],
          message: this.message,
        };
        const response = await apiClient.post("/conversations", newPayload);
        const conversationId = response.data.conversation.id;
        const messageResponse = await apiClient.get(
          `/conversations/${conversationId}/messages`
        );
        const newMessage = {
          id: messageResponse.data.data[0].id,
          text: this.message,
          timestamp: new Date().toLocaleTimeString([], {
            hour: "2-digit",
            minute: "2-digit",
          }),
          isOutgoing: true,
          user_profile: {
            fullname: this.currentUserFullName,
          },
        };
        this.currentMessages.push(newMessage);
        this.newConversationPayload = null;
        this.sendingMessage = false;
      } else {
        let payload = {
          conversation_id: this.currentParticipant?.conversation_id,
          message: this.message,
        };

        try {
          const response = await apiClient.post("/sendMessage", payload);
          const newMessage = {
            id: response.data.data.id,
            text: this.message,
            timestamp: new Date().toLocaleTimeString([], {
              hour: "2-digit",
              minute: "2-digit",
            }),
            isOutgoing: true,
            user_profile: {
              fullname: this.currentUserFullName,
            },
          };
          this.currentMessages.push(newMessage);
        } catch (error) {
          console.error("Error sending message:", error);
        }
      }
      // Reset the message input field
      this.message = "";
      this.sendingMessage = false;
    },

    deleteMessage(messageId) {
      this.selectedMessageId = messageId;
      this.dialog = true;
    },
    confirmDelete() {
      apiClient
        .post(
          "/deleteMessage",
          { id: this.selectedMessageId },
          { withCredentials: true }
        )
        .then(() => {
          this.currentMessages = this.currentMessages.filter(
            (message) => message.id !== this.selectedMessageId
          );
          this.dialog = false;
          this.selectedMessageId = null;
        })
        .catch((error) => {
          console.error("There was an error deleting the message:", error);
          this.dialog = false;
          this.selectedMessageId = null;
        });
    },

    handleResize() {
      this.isDesktop = window.innerWidth > 950;
    },
    fetchConversations() {
      apiClient
        .get("/getUsersConversations", { withCredentials: true })
        .then((response) => {
          if (response.data.success) {
            this.otherParticipants = response.data.otherParticipants;
          }
        })
        .catch((error) => {
          console.error(
            "There was an error fetching the conversations: ",
            error
          );
        });
    },

    async fetchConversationMessages(initialLoad = true) {
      if (
        this.allMessagesLoaded ||
        !this.currentParticipant ||
        this.loadingMessages
      )
        return;

      this.loadingMessages = true;
      try {
        const conversationId = this.currentParticipant.conversation_id;
        const offset = initialLoad ? 0 : this.messagesOffset;
        const response = await apiClient.get(
          `/conversations/${conversationId}/messages`,
          { params: { offset, limit: this.limit } }
        );

        const newMessages =
          response.data.data instanceof Array
            ? response.data.data
            : Object.values(response.data.data);

        if (newMessages.length > 0) {
          const formattedMessages = newMessages.map((message) => ({
            id: message.id,
            text: message.message,
            timestamp: new Date(message.created_at).toLocaleTimeString([], {
              hour: "2-digit",
              minute: "2-digit",
            }),
            isOutgoing: message.user_id === this.currentUserId,
            user_profile: message.user_profile,
          }));
          this.currentMessages = initialLoad
            ? formattedMessages.reverse()
            : [...formattedMessages.reverse(), ...this.currentMessages];
          this.messagesOffset += formattedMessages.length;
          this.allMessagesLoaded = formattedMessages.length < this.limit;

          if (initialLoad) this.scrollToBottom();
        } else {
          this.allMessagesLoaded = true;
        }
      } catch (error) {
        console.error("Error fetching messages:", error);
      } finally {
        this.loadingMessages = false;
      }
    },

    scrollToBottom() {
      this.$nextTick(() => {
        const container = this.$refs.messagesContainer;
        container.scrollTop = container.scrollHeight;
      });
    },

    getImagePath(imagePath) {
      const baseUrl = "http://localhost:8000";
      return baseUrl + imagePath;
    },
  },

  created() {
    window.addEventListener("resize", this.handleResize);
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.handleResize);
  },
  computed: {
    ...mapGetters(["getUserId"]),
    ...mapGetters(["getUserFullName"]),
    currentUserId() {
      return this.getUserId;
    },

    currentUserFullName() {
      return this.getUserFullName;
    },
    icon() {
      return this.icons[this.iconIndex];
    },
  },
  watch: {
    currentMessages() {
      this.$nextTick(() => {
        if (this.shouldScrollToBottom) {
          this.scrollToBottom();
        }
      });
    },
  },
};
</script>

<style scoped>

.chat-name-div .v-btn{
  margin: 0 10px;
}

.chat-name-div{
  display: flex;
  flex-direction: row;
  align-items: center;
}

.v-infinite-scroll__side {
  display: none !important;
}

.v-expansion-panel-text__wrapper {
  padding: 10px !important;
  display: flex;
  flex-direction: column;
}
.v-expansion-panel-text__wrapper .v-btn {
  margin: 2px 0;
}
.message-sender {
  font-weight: bold;
  margin-bottom: 4px;
}

.messages-container {
  flex-grow: 1;
  overflow-y: auto;
  padding: 10px;
  background: #f0f0f0;
  height: 400px;
}

.message {
  margin-bottom: 10px;
  padding: 8px;
  border-radius: 8px;
  max-width: 80%;
}

.outgoing {
  background-color: #d1e7dd;
  align-self: flex-end;
  margin-left: auto;
}

.incoming {
  background-color: #f8d7da;
  align-self: flex-start;
}

.timestamp {
  font-size: 0.75rem;
  text-align: right;
  margin-top: 4px;
}
.text-left {
  justify-content: flex-start;
}
.profile-picture {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  margin-right: 8px;
  object-fit: cover;
}
.body-div {
  width: 100%;
}
h2 {
  display: inline-block;
  font-size: 15px;
  color: rgb(170, 167, 167);
  font-weight: 400;
}
.main-div {
  padding: 0 200px;
  max-height: 100%;
}

.contentDiv {
  display: flex;
  justify-content: space-around;
  border-radius: 20px;
  background-color: #f8f9fa;
  max-height: 80%;
}
.contact {
  padding: 2px;
  padding-right: 0;
  max-width: 300px;
}
.chat-room {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  width: 100%;
  min-height: 400px;
  border-left: 1px solid #dddfdf;
  padding-bottom: 10px;
  box-sizing: border-box;
}

.chat-name {
  height: 100px;
  width: 100%;
  border-bottom: 1px solid rgb(234, 224, 224);
  display: flex;
  padding: 0 10px;
  align-items: center;
}

.chat-selection {
  width: 100%;
}

.delete-icon {
  float: right;
  cursor: pointer;
  color: #c00;
}

.fieldDiv{
  width: auto;
}

@media (max-width: 1200px) {
  .main-div {
    padding: 10px;
  }
}
@media (max-width: 600px) {


  .main-div {
    padding: 0;
  }

  .contentDiv {
    flex-direction: column;
  }

  .contact,
  .chat-room {
    width: 100%;
    max-width: none;
    padding: 0;
  }

  .chat-room {
    border-left: none;
  }

  .v-expansion-panel-text__wrapper {
    padding: 5px;
  }

  .message-input {
    padding: 0 10px;
  }

  .messages-container {
    height: calc(100vh - 200px);
  }
  .v-infinite-scroll__side {
    display: none !important;
  }
}
</style>
