<template>
  <div
    class="
      row
      justify-content-center
      login login-signin-on
      d-flex
      flex-column flex-lg-row flex-column-fluid
      bg-white
    "
  >
    <div class="login-aside d-none d-md-flex flex-column flex-row-auto justify-content-center position-relative">
      <div
        class="aside-img  d-md-flex flex-column flex-row-auto justify-content-center p-3"
        style="background-image: url('https://picsum.photos/600')"
      >
        <img class="img-fluid" src="logo.jpeg" alt="./logo.jpeg" />
      </div>
    </div>
    <div
      class="
        login-content
        flex-row-fluid
        d-flex
        flex-column
        justify-content-center
        position-relative
        overflow-hidden
        p-7
        mx-auto
      "
    >
      <div class="login-form login-signin">
        <div class="pb-5">
          <h2 class="py-2 font-weight-bold">Bienvenido a Tecnoplus</h2>
          <span class="text-muted">Ingresa tus datos de usuario</span>
        </div>
        <form id="form_login" autocomplete="off" @submit.prevent="login">
          <div class="form-group">
            <label class="font-weight-bold" for="username"
              >Usuario o email</label
            >
            <input
              type="text"
              class="form-control p-2 form-control-solid"
              id="username"
              aria-describedby="usernameHelp"
              name="username"
              required
              v-model="formValues.username"
            />
            <small id="usernameHelp" class="form-text text-danger">{{
              formErrors.username
            }}</small>
          </div>
          <div class="form-group">
            <label class="font-weight-bold" for="password">Contraseña</label>
            <input
              type="password"
              class="form-control p-2 form-control-solid"
              id="password"
              aria-describedby="passwordHelp"
              name="password"
              required
              v-model="formValues.password"
            />
            <small id="passwordHelp" class="form-text text-danger">{{
              formErrors.password
            }}</small>
          </div>
          <div class="form-group text-right">
            <button
              type="submit"
              class="btn btn-primary w-25 p-2 font-weight-bold"
            >
              Acceder
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Login",
  data() {
    return {
      data: "Login",
      formValues: {
        username: "",
        password: "",
      },
      formErrors: {
        username: "",
        password: "",
      },
    };
  },
  created() {},
  methods: {
    login() {
      let config = {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      };

      this.formErrors.username = "";
      this.formErrors.password = "";

      const formLogin = document.getElementById("form_login");
      axios
        .post("api/login", new FormData(formLogin), config)
        .then((response) => {
          response = response.data;
          if (
            response.status == "success" &&
            typeof response.user === "object"
          ) {
            localStorage.setItem("token", response.user.api_token);
            localStorage.setItem("user", JSON.stringify(response.user));
            this.$router.push(this.$route.query.redirect || "/");
          }
        })
        .catch((error) => {
          var errors = error.response.data.errors;

          if (typeof errors != "undefined") {
            if (typeof errors.username != "undefined") {
              this.formValues.username = "";
              this.formValues.password = "";

              this.formErrors.username = errors.username[0];
            }

            this.formValues.password = "";
          } else {
            this.formValues.password = "";
            this.formErrors.password = error.response.data.message;
          }

          console.log(error.response);
        });
    },
  },
};
</script>
