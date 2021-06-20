<template>
    <div>
       <header class="border-bottom bg-white">
           <nav class="navbar navbar-expand-lg navbar-light bg-light">
               <div class="container">
                   <a class="navbar-brand" href="#">Filmovie</a>
                   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                       <span class="navbar-toggler-icon"></span>
                   </button>

                   <div class="collapse navbar-collapse" id="navbarSupportedContent">
                       <ul class="navbar-nav mr-right">
                           <router-link to="/" class="nav-item nav-link">Home</router-link>
                           <router-link to="/movies" class="nav-item nav-link">Movies</router-link>
                           <router-link v-if="!isLogged" to="/login" class="nav-item nav-link">Login</router-link>
                           <router-link v-if="!isLogged" to="/register" class="nav-item nav-link">Register</router-link>
                           <router-link v-if="isLogged && isAdmin" to="/admin" class="nav-item nav-link">Administracija</router-link>
                           <li style="cursor: pointer" v-on:click="logout" v-if="isLogged" class="nav-item nav-link cursor">Logout</li>
                       </ul>
                   </div>
               </div>
           </nav>
       </header>
        <div id="content">
            <router-view></router-view>
        </div>
        <footer class="footer bg-white border-top">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 pt-2 pb-2">
                        <span class="text-muted">Napravila Marina Vucković.</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
<style>
    body{
        padding-bottom: 76px;
    }
    .footer {
        position: fixed;
        bottom: 0;
        width: 100vw;
        line-height: 60px;
        background-color: #f5f5f5;
    }
</style>
<script>
    import User from "./Helpers/User";
    import AppStorage from "./Helpers/AppStorage";

    export default {
        created() {
            this.isLogged = User.loggedIn(),
            this.isAdmin = User.isAdmin()
        },
        data() {
            return {
                isLogged: User.loggedIn(),
                isAdmin:  User.isAdmin()
            }
        },
        watch:{
            $route (to, from){
                this.isLogged = User.loggedIn()
                this.isAdmin = User.isAdmin()
            }
        },
        methods: {
            logout() {
                AppStorage.clear();
                this.isLogged = false;
                this.$router.push({name: 'login'});
            }
        }
    }
</script>
