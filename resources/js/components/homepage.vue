<template>
    <div class="container">
        <agile v-if="movies.length" :options="options">
            <div v-for="movie in movies" class="slide">
                <img class="slider-image" v-bind:src="movie.slider_image" v-bind:alt="movie.name">
            </div>
        </agile>
        <div class="row m-t-5">
            <div class="col-md-4 mb-4" v-for="movie in movies">
                <div v-on:click="goToSingleMovie(movie.id)" class="card mb-4 box-shadow">
                    <img v-if="movie.poster" v-bind:src="movie.poster" class="card-img-top" v-bind:alt="movie.name" style="width: 100%; height: auto; max-height: 350px; display: block;"  data-holder-rendered="true">
                    <img v-if="!movie.poster" src="https://via.placeholder.com/348x350" alt="">
                    <div style="cursor: pointer" class="card-body pointer">
                        <p class="font-weight-bold m-0">{{ movie.name }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <small class="text-muted">{{ movie.year }}</small>
                        </div>
                        <p v-if="movie.description && movie.description.length < 50" class="card-text">{{ movie.description }}</p>
                        <p v-if="movie.description && movie.description.length > 50" class="card-text">{{ movie.description.substring(0,50) + '..' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .slider-image {
        height: 550px;
        object-fit: cover;
        object-position: center center;
        width: 100%;
    }
    .agile__dots {
        margin-top: 10px;
    }
    .agile__dot button {
        border-radius: 50%;
        height: 12px;
        width: 12px;
        display: block;
        color: black;
        background: black;
        border: none;
        margin: 0 3px;
    }
    .agile__dot--current button{
        background-color: #1d68a7;
    }
</style>
<script>
    import { VueAgile } from 'vue-agile';

    export default {
        data() {
            return {
                movies: {},
                options: {
                    dots: true,
                    fade: false,
                    navButtons: false,
                    infinite: true,
                    autoplay: true
                }
            };
        },
        mounted() {
            this.getMovie();
        },
        components: {
            agile: VueAgile
        },
        methods: {
            goToSingleMovie(id){
                console.log('asdasd');
                this.$router.push({ name: 'singleMovie', params: {id: id} })
            },
            getMovie() {
                Vue.axios.get('http://filmovie.test/api/movies' )
                    .then(response => {
                        this.movies = response.data.data;
                    });
            }
        }
    }
</script>
