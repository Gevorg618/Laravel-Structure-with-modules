<template>
  <div>
    <b-loading :is-full-page="false" 
               :active.sync="isLoading" />
    <div class="pdd-horizon-30">
      <p class="mrg-btm-15 font-size-13">Please enter a new password.</p>
      <form class="form-horizontal pdd-right-30" @submit.prevent="onSubmit">
        <input v-model.trim="token" type="hidden">
        <input v-model.trim="email" type="hidden">
        <div class="form-group row">
          <label for="password" class="col-md-4 control-label">Password</label>
          <div class="col-md-8">
            <input v-model.trim="$v.password.$model" 
                   :class="{ 'is-invalid': $v.password.$error }" 
                   type="password" 
                   class="form-control"
                   placeholder="Password">
            <div v-if="$v.password.$error" class="invalid-feedback">Enter a valid password (minimum of 8 characters).</div>
          </div>
        </div>
        <div class="form-group row">
          <label for="passwordConfirmation" class="col-md-4 control-label">Confirm Password</label>
          <div class="col-md-8">
            <input v-model.trim="$v.passwordConfirmation.$model" 
                   :class="{ 'is-invalid': $v.passwordConfirmation.$error }" 
                   type="password" 
                   class="form-control"
                   placeholder="Confirm Password">
            <div v-if="$v.passwordConfirmation.$error" class="invalid-feedback">Password Confirmation must match password.</div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="mrg-top-20 pull-right">
              <button :disabled="$v.$invalid" class="button is-primary">Submit</button>
            </div>
          </div>
        </div>
        <b-message v-if="error" 
                   class="mrg-top-20" 
                   type="is-danger">
          {{ error }}
        </b-message>
      </form>
    </div>
  </div>
</template>

<script>
import { isEmpty } from "lodash";
import { mapActions } from "vuex";
import ViewPort from "@/mixins/viewport";
import { required, email, minLength, sameAs } from "vuelidate/lib/validators";
export default {
  mixins: [ViewPort],
  props: {
    token: {
      type: String,
      required: true
    },
    email: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      isLoading: false,
      error: null,
      password: "",
      passwordConfirmation: ""
    };
  },
  validations: {
    email: {
      required,
      email
    },
    token: {
      required
    },
    password: {
      required,
      minLength: minLength(8)
    },
    passwordConfirmation: {
      required,
      sameAsPassword: sameAs("password")
    }
  },
  metaInfo: {
    title: "Password Reset Form"
  },
  methods: {
    ...mapActions("auth", ["resetPassword"]),
    onSubmit() {
      if (this.$v.$invalid) {
        return false;
      }

      this.error = null;
      this.isLoading = true;

      this.resetPassword({
        email: this.email,
        token: this.token,
        password: this.password,
        passwordConfirmation: this.passwordConfirmation
      })
        .then(result => {
          this.$toast.open({
            message: "Password Reset Completed.",
            type: "is-success"
          });
          this.$router.push({ name: "login.index" });
        })
        .catch(response => (this.error = response.errors.join("<br />")))
        .then(() => {
          this.isLoading = false;
        });
    }
  }
};
</script>

<style>
</style>
