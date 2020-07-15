<template>
    <div class="container mt-5">
        <div class="row justify-content-md-center">
            <div class="col-6">
                <div class="card card-default">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <div v-if="validationError" class="alert alert-danger">
                           <p class="m-0">{{ validationError.error }}</p>
                        </div>
                        <form autocomplete="off" method="post" @submit.prevent="login">
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" id="email" class="form-control" placeholder="user@example.com" v-model="form.email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" class="form-control" v-model="form.password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Signin</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        created() {
            if (User.loggedIn()) {
                this.$router.push({ name: 'home'})
            }
        },
        data() {
            return {
                form:{
                    email: null,
                    password: null
                },
                validationError: null
            }
        },
        methods: {
          login() {
              Vue.axios.post('/api/auth/login', this.form)
                .then(res => {
                    User.responseAfterLogin(res);
                    this.$router.push({ name: 'home'});
                })
                .catch(error => this.validatioError = error.response.data)
          }
        }
    }
</script>
