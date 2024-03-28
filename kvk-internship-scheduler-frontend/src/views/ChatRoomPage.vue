<template>
  <custom-header v-if="isDesktop" />
  <mobile-nav v-if="!isDesktop" />
  <div class="bodyDiv">
    <div class="mainDiv">
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
              @input="searchUsers"
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
                <v-btn @click="newChatModal = true">Naujas pokalbis</v-btn>
              </v-expansion-panel-text>
            </v-expansion-panel>
            <v-expansion-panel
              style="background-color: #f8f9fa"
              title="Grupės"
              @click="fetchGroupConversations"
            >
              <v-expansion-panel-text>
                <v-btn @click="newGroupModal = true">Nauja grupė</v-btn>
                <v-btn
                  v-for="group in groups"
                  :key="group.conversation_id"
                  class="group-button"
                  @click="selectGroup(group.conversation_id)"
                >
                  {{ group.group_name }}
                </v-btn>
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
          <div class="chat-name" v-if="currentParticipant">
            <img
              v-if="currentParticipant.image_path"
              :src="getImagePath(currentParticipant.image_path)"
              alt="Profile Picture"
              class="profile-picture"
            />
            <h3>{{ currentParticipant ? currentParticipant.fullname : "" }}</h3>
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
import { mapGetters } from "vuex";
import userSearch from "@/components/SearchUserProfile.vue";

export default {
  components: { customHeader, mobileNav, userSearch },

  data() {
    return {
      groups: [],
      newGroupModal: false,
      groupInfo: {
        name: "",
        selectedUsers: [],
      },
      searchResults: [],
      selectedGroupUsers: [],
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
    };
  },

  methods: {
    selectGroup(conversationId) {
      const selectedGroup = this.groups.find(
        (group) => group.conversation_id === conversationId
      );

      this.currentParticipant = {
        conversation_id: conversationId,
        fullname: selectedGroup.group_name,
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

    createGroup() {
      const payload = {
        name: this.groupInfo.name,
        type: "group",
        userProfileId: this.groupInfo.selectedUsers,
      };

      apiClient
        .post("/conversations", payload)
        .then((response) => {
          console.log("Group created successfully:", response.data);
          this.groupInfo.name = "";
          this.groupInfo.selectedUsers = [];
          this.newGroupModal = false;
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
            console.log("Search results:", response.data);
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

      console.log("useris ", user.id);
      console.log("egzistuoja? ", existingConversation);

      if (existingConversation) {
        this.selectConversation(existingConversation);
      } else {
        // Add a check to ensure that the user object is not null or undefined
        if (user) {
          console.log("useris ife ", user);
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
      // Update currentParticipant and other relevant states
      this.currentParticipant = conversation;
      this.currentMessages = []; // Clear current messages before fetching new ones

      // Fetch messages for the selected conversation
      this.fetchConversationMessages(true);
    },
    //clears the messages of older conversations
    setupNewConversation(user) {
      // Prepare the state for a new conversation with the selected user
      this.currentParticipant = {
        fullname: user.fullname,
        image_path: user.image_path,
        conversation_id: null,
      };
      this.currentMessages = []; // Clear any previous messages shown
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
        const messageResponse = await apiClient.get(`/conversations/${conversationId}/messages`);
        const newMessage = {
          id:messageResponse.data.data[0].id,
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
  mounted() {
    console.log("Current User Fullname:", this.currentUserFullName);
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
.bodyDiv {
  width: 100%;
}
h2 {
  display: inline-block;
  font-size: 15px;
  color: rgb(170, 167, 167);
  font-weight: 400;
}
.mainDiv {
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
.contact #contact-info .v-expansion-panel-text__wrapper {
  padding: 0 !important;
}
.delete-icon {
  float: right;
  cursor: pointer;
  color: #c00;
}
</style>
