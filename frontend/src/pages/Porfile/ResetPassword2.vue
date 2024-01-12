<template>
  <q-page class="row justify-center items-center">
    <div
      class="column col-12 col-sm-8 col-md-6 col-lg-4"
      style="margin: 1rem 0"
    >
      <div class="row">
        <q-card
          bordered
          class="q-pa-lg shadow-1 col-11"
          style="margin: auto; padding: 2rem"
        >
          <q-card-section class="text-center" style="margin-bottom: 2rem">
            <h4 style="font-weight: 700; color: #1976d2">Reset Password</h4>
          </q-card-section>
          <q-form @submit.prevent="formSubmit" class="q-gutter-md">
            <div class="form-group">
              <label for="new_password">Old Password</label>
              <input
                @blur="handleBlur"
                @input="inputField"
                v-model="formData.old_password"
                type="password"
                class="form-control"
                id="old_password"
                placeholder="Old Password"
              />
              <!--validation message -->
              <p
                v-if="errors.old_password"
                style="color: red"
                class="text-xs mt-1"
              >
                <span v-if="errors.old_password[0]">{{
                  errors.old_password[0]
                }}</span>
                <span v-else>{{ errors.old_password }}</span>
              </p>
            </div>

            <!-- new password -->
            <div class="form-group">
              <label for="new_password">New Password</label>
              <input
                @blur="handleBlur"
                @input="inputField"
                v-model="formData.password"
                type="password"
                class="form-control"
                id="new_password"
                placeholder="New Password"
              />
              <!-- new changes -->
              <div
                v-if="formData.password"
                :class="{
                  weak: isPasswordWeak,
                  strong: isPasswordStrong,
                  medium: isPasswordMeduim,
                }"
                class="passStr"
              ></div>
              <div
                v-if="formData.password"
                class="strMessage"
                :class="{
                  danger: isPasswordWeak,
                  goodColor: isPasswordMeduim,
                  strongColor: isPasswordStrong,
                }"
              >
                {{ strMessage }}
              </div>
              <!-- new changes -->
              <div class="show_error" v-if="!isValidPassword">
                <p>Password must meet the following requirements</p>
                <div style="display: flex; flex-direction: column">
                  <strong style="color: green" v-if="smallLetterMsgPass"
                    >✅ At least one letter</strong
                  >
                  <strong style="color: red" v-if="capitalLetterMsgPass"
                    >❌ At least one capital letter</strong
                  >
                  <strong style="color: green" v-if="numberMsgPass"
                    >✅ At least one one number</strong
                  >
                  <strong style="color: red" v-if="specialCharacterMsgPass"
                    >❌ At least one special symbol</strong
                  >
                  <strong style="color: red" v-if="eightCharactersMsgPass"
                    >✅ Be at least 8 character</strong
                  >
                </div>
              </div>
              <p v-if="errors.password" style="color: red" class="text-xs mt-1">
                {{ errors.password[0] }}
              </p>
            </div>

            <!-- confirm new password -->
            <div class="form-group">
              <label for="cofirm_new_password">Confrim New Password</label>
              <input
                @blur="handleBlur"
                @input="inputField"
                v-model="formData.confirm_new_password"
                type="password"
                class="form-control"
                id="cofirm_new_password"
                placeholder="Confirm New Password"
              />
              <!-- new changes -->
              <div
                v-if="confirmStrMessage"
                :class="{
                  weak: isConfirmPasswordWeak,
                  strong: isConfirmPasswordStrong,
                  medium: isConfirmPasswordMeduim,
                }"
                class="passStr"
              ></div>
              <div
                v-if="confirmStrMessage"
                class="strMessage"
                :class="{
                  danger: isConfirmPasswordWeak,
                  goodColor: isConfirmPasswordMeduim,
                  strongColor: isConfirmPasswordStrong,
                }"
              >
                {{ confirmStrMessage }}
              </div>

              <!-- new changes -->
              <div class="show_error" v-if="!isValidConfirmPassword">
                <p>Password must meet the following requirements</p>
                <div style="display: flex; flex-direction: column">
                  <strong style="color: green" v-if="smallLetterMsg"
                    >✅ At least one letter</strong
                  >
                  <strong style="color: red" v-if="capitalLetterMsg"
                    >❌ At least one capital letter</strong
                  >
                  <strong style="color: green" v-if="numberMsg"
                    >✅ At least one one number</strong
                  >
                  <strong style="color: red" v-if="specialCharacterMsg"
                    >❌ At least one special symbol</strong
                  >
                  <strong style="color: red" v-if="eightCharactersMsg"
                    >✅ Be at least 8 character</strong
                  >
                </div>
              </div>
              <p
                v-if="errors.confirm_new_password"
                class="text-xs mt-1"
                style="color: red"
              >
                {{ errors.confirm_new_password[0] }}
              </p>
            </div>

            <q-btn type="submit" :disabled="!isFormIncomplete" color="primary">
              Save Changes
            </q-btn>
          </q-form>
        </q-card>
      </div>
    </div>
  </q-page>
</template>
<script setup>
import { reactive, ref, watch } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import { useRouter } from "vue-router";

const router = useRouter();

const user = ref("");
user.value = JSON.parse(localStorage.getItem("user"));

const errors = ref({});
const messages = ref({});
let formData = reactive({
  id: user.value.id,
  old_password: "",
  password: "",
  confirm_new_password: "",
});

const regexPattern = /((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8})/;
const isValidPassword = ref(true);
const isValidConfirmPassword = ref(true);
const isFormIncomplete = ref(false);

// new changes
const isPasswordWeak = ref(false);
const isPasswordMeduim = ref(false);
const isPasswordStrong = ref(false);
const strMessage = ref("");

// confirm password
const isConfirmPasswordWeak = ref(false);
const isConfirmPasswordMeduim = ref(false);
const isConfirmPasswordStrong = ref(false);
const confirmStrMessage = ref("");
// new changes

// confirm password error message
const smallLetterMsg = ref(false);
const capitalLetterMsg = ref(false);
const specialCharacterMsg = ref(false);
const numberMsg = ref(false);
const eightCharactersMsg = ref(false);
// password error message
const smallLetterMsgPass = ref(false);
const capitalLetterMsgPass = ref(false);
const specialCharacterMsgPass = ref(false);
const numberMsgPass = ref(false);
const eightCharactersMsgPass = ref(false);
// error message for strength change
const regexWeak = /^.{1,5}$/; // Password with up to 5 characters
const regexMedium = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/; // Password with at least one lowercase letter, one uppercase letter, and one digit, and minimum length of 8
const regexStrong = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()]).{8,}$/; // Password with at least one lowercase letter, one uppercase letter, and one digit, and minimum length of 8

const inputField = () => {
  // my new changes///////////////////////////////////////////////////////////////////////////////////////

  // start of confirm new password
  if (!/(?=.*[a-z])/.test(formData.confirm_new_password)) {
    smallLetterMsg.value = true;
  } else {
    smallLetterMsg.value = false;
  }
  if (!/(?=.*[A-Z])/.test(formData.confirm_new_password)) {
    capitalLetterMsg.value = true;
  } else {
    capitalLetterMsg.value = false;
  }
  if (!/(?=.*\d)/.test(formData.confirm_new_password)) {
    numberMsg.value = true;
  } else {
    numberMsg.value = false;
  }
  if (!/.{8,}/.test(formData.confirm_new_password)) {
    eightCharactersMsg.value = true;
  } else {
    eightCharactersMsg.value = false;
  }
  if (!/[!@#$%^&*()]/.test(formData.confirm_new_password)) {
    specialCharacterMsg.value = true;
  } else {
    specialCharacterMsg.value = false;
  }
  if (formData.confirm_new_password.length >= 8) {
    isValidConfirmPassword.value = regexPattern.test(
      formData.confirm_new_password
    );
  } else if (formData.confirm_new_password.length == 0) {
    isValidConfirmPassword.value = true;
  }
  // end cnofirm password
  // start of password
  if (!/(?=.*[a-z])/.test(formData.password)) {
    smallLetterMsgPass.value = true;
  } else {
    smallLetterMsgPass.value = false;
  }
  if (!/(?=.*[A-Z])/.test(formData.password)) {
    capitalLetterMsgPass.value = true;
  } else {
    capitalLetterMsgPass.value = false;
  }
  if (!/(?=.*\d)/.test(formData.password)) {
    numberMsgPass.value = true;
  } else {
    numberMsgPass.value = false;
  }
  if (!/.{8,}/.test(formData.password)) {
    eightCharactersMsgPass.value = true;
  } else {
    eightCharactersMsgPass.value = false;
  }
  if (!/[!@#$%^&*()]/.test(formData.password)) {
    specialCharacterMsgPass.value = true;
  } else {
    specialCharacterMsgPass.value = false;
  }
  if (formData.confirm_new_password.length >= 8) {
    isValidConfirmPassword.value = regexPattern.test(
      formData.confirm_new_password
    );
  } else if (formData.confirm_new_password.length == 0) {
    isValidConfirmPassword.value = true;
  }
  if (formData.password.length >= 8) {
    isValidPassword.value = regexPattern.test(formData.password);
  } else if (formData.password.length == 0) {
    isValidPassword.value = true;
  }
  // end of password/////

  // my new changes////////////////////////////////////////////////////////////////////////////////

  if (formData.password.length == 0) {
    isPasswordWeak.value = false;
    isPasswordMeduim.value = false;
    isPasswordStrong.value = false;
    strMessage.value = "";
  } else if (regexWeak.test(formData.password)) {
    isPasswordWeak.value = true;
    isPasswordMeduim.value = false;
    isPasswordStrong.value = false;
    strMessage.value = "Weak❗";
  } else if (regexStrong.test(formData.password)) {
    isPasswordWeak.value = false;
    isPasswordMeduim.value = false;
    isPasswordStrong.value = true;
    strMessage.value = "Great✅";
  } else if (regexMedium.test(formData.password)) {
    isPasswordWeak.value = false;
    isPasswordMeduim.value = true;
    isPasswordStrong.value = false;
    strMessage.value = "Good";
  }

  // confirm passwrod ///////////////////
  if (formData.confirm_new_password.length == 0) {
    isConfirmPasswordWeak.value = false;
    isConfirmPasswordMeduim.value = false;
    isConfirmPasswordStrong.value = false;
    confirmStrMessage.value = "";
  } else if (regexWeak.test(formData.confirm_new_password)) {
    isConfirmPasswordWeak.value = true;
    isConfirmPasswordMeduim.value = false;
    isConfirmPasswordStrong.value = false;
    confirmStrMessage.value = "Weak❗";
  } else if (regexStrong.test(formData.confirm_new_password)) {
    isConfirmPasswordWeak.value = false;
    isConfirmPasswordMeduim.value = false;
    isConfirmPasswordStrong.value = true;
    confirmStrMessage.value = "Great✅";
  } else if (regexMedium.test(formData.confirm_new_password)) {
    isConfirmPasswordWeak.value = false;
    isConfirmPasswordMeduim.value = true;
    isConfirmPasswordStrong.value = false;
    confirmStrMessage.value = "Good";
  }
};

const handleBlur = () => {
  if (formData.password.length > 0) {
    isValidPassword.value = regexPattern.test(formData.password);
  } else {
    isValidPassword.value = true;
  }
  if (formData.confirm_new_password.length > 0) {
    isValidConfirmPassword.value = regexPattern.test(
      formData.confirm_new_password
    );
  } else {
    isValidConfirmPassword.value = true;
  }
};

watch(formData, () => {
  if (
    regexPattern.test(formData.confirm_new_password) &&
    regexPattern.test(formData.password) &&
    formData.old_password.length > 0
  ) {
    return (isFormIncomplete.value = true);
  }
  return (isFormIncomplete.value = false);
});

// new submit form
const formSubmit = async () => {
  try {
    await axios
      .post(`http://127.0.0.1:8000/api/user/reset-password`, formData, {
        headers: { Authorization: `Bearer ${user.value.token}` },
      })
      .then((res) => {
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "Password Successfully Changed",
          showConfirmButton: false,
          timer: 2000,
        });
        if (user.value.id == 1) {
          router.push("/admin-profile");
        } else {
          router.push("/profile");
        }
      });
  } catch (error) {
    if (error.response.status === 422) {
      errors.value = error.response.data.errors;
    }
  }
};
</script>

<style scoped>
/* new style added */
.swal2-popup {
  z-index: 99999;
}
.q-header {
  z-index: 1;
}
.form-group {
  display: flex;
  flex-direction: column;
}
.form-group > label {
  font-size: 1rem;
  margin-bottom: 8px;
  color: #616060;
}
.form-group > input {
  border: 1px solid #bbb6b6;
  border-radius: 5px;
  padding: 8px;
  outline: none;
}
.form-group > input::placeholder {
  color: rgb(185, 182, 182);
}

.show_error {
  margin-top: 1rem;
  padding: 1rem;
  box-shadow: 1px 1px 1px #e5e2e6, 1px 1px 1px #e5e2e6 inset;
  border-radius: 3px;
  color: #000;
  font-size: 0.825rem;
}
.passStr {
  margin-top: 5px;
  width: 0;
  height: 7px;
  border-radius: 5px;
}
.strMessage {
  width: 100%;
  display: flex;
  justify-content: end;
  font-size: 12px;
  font-weight: bold;
}
.danger {
  color: red;
}
.goodColor {
  color: rgb(184, 187, 28);
}
.strongColor {
  color: green;
}
.weak {
  width: 33%;
  background: red;
  transition: width 1s ease;
}
.medium {
  width: 70%;
  background: rgb(226, 230, 23);
  transition: width 1s ease;
}
.strong {
  width: 100%;
  background: green;
  transition: width 1s ease;
}
</style>
