<template>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 mt-5">
                <h1>Administration</h1>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-6">
                <div class="form-group">
                    <b-form-select id="exampleFormControlSelect1" v-model="selected" :options="options">
                        <template v-slot:first>
                            <b-form-select-option :value="null" disabled>-- Please select an option --</b-form-select-option>
                        </template>
                    </b-form-select>
                </div>
            </div>
            <actor-modal v-if="selected === 'actors'" title="Add actor"></actor-modal>
            <category-modal v-if="selected === 'category'" title="Add category"></category-modal>
            <director-modal v-if="selected === 'directors'" title="Add director"></director-modal>
            <movie-modal v-if="selected === 'movies'" title="Add movie"></movie-modal>

        </div>
        <div class="row">
            <div class="col-md-12">
                <category v-if="selected === 'category'"></category>
                <actor v-if="selected === 'actors'"></actor>
                <director v-if="selected === 'directors'"></director>
                <movie v-if="selected === 'movies'"></movie>
            </div>
        </div>
    </div>
</template>
<script>
    import Category from './category'
    import Movie from './movie'
    import Actor from './actor'
    import Director from './director'
    import ActorModal from "./actor-modal";
    import CategoryModal from './category-modal'
    import DirectorModal from "./director-modal";
    import MovieModal from "./movie-modal";

    export default {
        created() {
            if (!User.loggedIn()) {
                this.$router.push({ name: 'login'})
            }
        },
        components: {
            MovieModal,
            ActorModal,
            DirectorModal,
            Category,
            CategoryModal,
            Actor,
            Director,
            Movie
        },
        data() {
            return {
                selected: null,
                options: [
                    { value: 'users', text: 'Users' },
                    { value: 'movies', text: 'Movies' },
                    { value: 'actors', text: 'Actors' },
                    { value: 'directors', text: 'Directors' },
                    { value: 'category', text: 'Category' },
                ]
            }
        }
    }
</script>
