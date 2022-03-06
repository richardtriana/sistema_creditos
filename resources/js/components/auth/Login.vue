<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">
        <h2 class="text-center py-2">¡INICIAR SESIÓN!</h2>
        <div class="row">
          <div class="d-none d-md-block col-6 text-center">
            <img class="img-fluid" src="https://picsum.photos/200/300" alt="" />
          </div>
          <div class="col-6">
            <form id="form_login" autocomplete="off" @submit.prevent="login">
              <div class="form-group">
                <label for="exampleInputUsername1">Usuario o email</label>
                <input
                  type="text"
                  class="form-control"
                  id="username"
                  aria-describedby="usernameHelp"
                  name="username"
                  placeholder="Ingresar username"
                  required
                  v-model="formValues.username"
                />
                <small id="usernameHelp" class="form-text text-danger">{{
                  formErrors.username
                }}</small>
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1">Contraseña</label>
                <input
                  type="password"
                  class="form-control"
                  id="password"
                  aria-describedby="passwordHelp"
                  name="password"
                  placeholder="Ingresar contraseña"
                  required
                  v-model="formValues.password"
                />
                <small id="passwordHelp" class="form-text text-danger">{{
                  formErrors.password
                }}</small>
              </div>
              <button type="submit" class="btn btn-primary">Acceder</button>
            </form>
          </div>
        </div>
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
