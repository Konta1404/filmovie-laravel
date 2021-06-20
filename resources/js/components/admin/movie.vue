<template>
    <b-container>
        <!-- User Interface controls -->
        <b-row class="mb-3">
            <movie-modal :method="getMovies" v-if="selectedObject === 'movies'" title="Add movie"></movie-modal>
        </b-row>
        <b-table
            v-if="movies.data"
            show-empty
            small
            stacked="md"
            :items="movies.data"
            :fields="fields"
            @click="v-b-modal.edit-modal"
        >

            <template v-slot:cell(actions)="row">
                <b-button v-b-modal.edit-modal v-on:click="saveId(row.item)" variant="info" edit-modal>Edit</b-button>
                <b-button variant="danger" v-on:click="deleteMovie(row.item)">Delete</b-button>
            </template>

            <template v-slot:row-details="row">
                <b-card>
                    <ul>
                        <li v-for="(value, key) in row.item" :key="key">{{ key }}: {{ value }}</li>
                    </ul>
                </b-card>
            </template>
        </b-table>
        <b-row>
            <pagination :data="movies" @pagination-change-page="getMovies"></pagination>
        </b-row>
        <b-modal
            id="edit-modal"
            ref="modal"
            title="Edit movie"
            @show="showModal"
            @hidden="resetModal"
            @ok="handleOk"
        >
            <form ref="form" @submit.stop.prevent="handleSubmit">
                <b-form-group
                    :state="states.nameState"
                    label="Name"
                    label-for="first-name-input"
                    invalid-feedback="First name is required"
                >
                    <b-form-input
                        id="first-name-input"
                        v-model="form.name"
                        :state="states.nameState"
                        required
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    :state="states.yearState"
                    label="Year"
                    label-for="year-input"
                    invalid-feedback="Year must be whole number"
                >
                    <b-form-input
                        id="year-input"
                        type="number"
                        step="1"
                        v-model="form.year"
                        :state="states.yearState"
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    :state="states.ratingState"
                    label="Rating"
                    label-for="rating-input"
                    invalid-feedback="Rating must be between 1 and 10"
                >
                    <b-form-input
                        id="name-input"
                        type="number"
                        min="1"
                        step="0.01"
                        max="10"
                        v-model="form.rating"
                        :state="states.ratingState"
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    :state="states.description"
                    label="Description"
                    label-for="description-input"
                >
                    <b-form-textarea
                        id="description-input"
                        v-model="form.description"
                        :state="states.description"
                    ></b-form-textarea>
                </b-form-group>
                <b-form-group
                    :state="states.poster"
                    label="Photo url"
                    label-for="poster-input"
                >
                    <b-form-input
                        id="poster-input"
                        v-model="form.poster"
                        :state="states.poster"
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    :state="states.sliderImageState"
                    label="Slider image url"
                    label-for="slider_image-input"
                >
                    <b-form-input
                        id="slider_image-input"
                        v-model="form.slider_image"
                        :state="states.sliderImageState"
                    ></b-form-input>
                </b-form-group>
                <label>Select directors</label>
                <tags-input element-id="tags"
                            v-model="formDirectors"
                            placeholder="Add directors"
                            :existing-tags="directors"
                            :only-existing-tags="true"
                            :typeahead="true"></tags-input>
                <label>Select actors</label>
                <tags-input element-id="tags"
                            v-model="formActors"
                            placeholder="Add actors"
                            :existing-tags="actors"
                            :only-existing-tags="true"
                            :typeahead="true"></tags-input>
                <label>Select categories</label>
                <tags-input element-id="tags"
                            placeholder="Add categories"
                            v-model="formCategories"
                            :existing-tags="categories"
                            :only-existing-tags="true"
                            :typeahead="true"></tags-input>
            </form>
        </b-modal>
    </b-container>
</template>

<script>
    import Noty from "noty";
    import moment from 'moment';
    import MovieModal from "./movie-modal";

    export default {
        props: {
            selectedObject: String
        },
        components: {
            MovieModal,
        },
        data() {
            return {
                movies: {},
                fields: ['id', 'name', 'created_at' , 'updated_at', 'actions'],
                name: '',
                movieItem: {},
                states: {
                    nameState: null,
                    lastNameState: null,
                    yearState: null,
                    ratingState: null,
                    posterState: null,
                    sliderImageState: null,
                },
                form: {
                    name: '',
                    year: '',
                    rating: 1,
                    poster: '',
                    slider_image: ''
                },
                formDirectors: [],
                formActors: [],
                formCategories: [],
                directors: [],
                actors: [],
                categories: []
            }
        },
        mounted() {
            this.getMovies();
            this.getItemsForMovies();
        },
        methods: {
            saveId(item) {
                this.movieItem = item;
            },
            getMovies(page = 1) {
                Vue.axios.get('/api/movies?page=' + page)
                    .then(response => {
                        this.movies = response.data;
                        this.moviesS = response;

                        for (let i = 0; i < this.movies.length; i++) {
                            this.movies.data[i].created_at = moment(this.movies[i].created_at).format('YYYY-MM-DD');
                            this.movies.data[i].updated_at = moment(this.movies[i].updated_at).format('YYYY-MM-DD');
                        }
                    });
            },
            getCategories() {
                Vue.axios.get('/api/categories')
                    .then(response => {
                        this.categories = response.data;
                        for (let i = 0; i < this.categories.length; i++) {
                            this.categories[i].created_at = moment(this.categories[i].created_at).format('YYYY-MM-DD');
                            this.categories[i].updated_at = moment(this.categories[i].updated_at).format('YYYY-MM-DD');
                        }

                    });
            },
            getItemsForMovies(){
                Vue.axios.all([
                    Vue.axios.get('/api/directors'),
                    Vue.axios.get('/api/actors'),
                    Vue.axios.get('/api/categories'),
                ]).then(Vue.axios.spread((directors, actors, categories) => {
                    this.directors = directors.data;
                    this.directors.forEach(element=>{
                        element.key = element.id;
                        element.value = element.first_name;
                        if (element.last_name !== null) {
                            element.value += ' ' + element.last_name;
                        }
                    })
                    this.actors = actors.data;
                    this.actors.forEach(element=>{
                        element.key = element.id;
                        element.value = element.first_name;
                        if (element.last_name !== null) {
                            element.value += ' ' + element.last_name;
                        }
                    })
                    this.categories = categories.data;
                    this.categories.forEach(element=>{
                        element.key = element.id;
                        element.value = element.name;
                    })
                }));
            },
            showModal(){
                this.resetModal();
                this.form = this.movieItem;
                console.log(this.movieItem);
                if (this.movieItem.categories !== []) {
                    this.movieItem.categories.forEach(category=> {
                        var data = category.category[0];
                        data.key = data.id;
                        data.value = data.name;
                        this.formCategories.push(data);
                    })
                }
                if (this.movieItem.directors !== []) {
                    this.movieItem.directors.forEach(director=> {
                        var data = director.director[0];
                        data.key = data.id;
                        data.value = data.first_name;
                        if (data.last_name !== null) {
                            data.value += ' ' + data.last_name;
                        }
                        this.formDirectors.push(data);
                    })
                }
                if (this.movieItem.actors !== []) {
                    this.movieItem.actors.forEach(actor=> {
                        var data = actor.actor[0];
                        data.key = data.id;
                        data.value = data.first_name;
                        if (data.last_name !== null) {
                            data.value += ' ' + data.last_name;
                        }
                        this.formActors.push(data);
                    })
                }
            },
            checkFormValidity() {
                const valid = this.$refs.form.checkValidity()
                this.nameState = valid
                return valid
            },
            resetModal() {
                this.name = ''
                this.nameState = null
            },
            handleOk(bvModalEvt) {
                // Prevent modal from closing
                bvModalEvt.preventDefault()
                // Trigger submit handler
                this.handleSubmit()
            },
            handleSubmit() {
                // Exit when the form isn't valid
                if (!this.checkFormValidity()) {
                    return
                }

                this.form.actors = this.formActors;
                this.form.directors = this.formDirectors;
                this.form.categories = this.formCategories;

                Vue.axios.put('/api/movies/' + this.movieItem.id, this.form)
                    .then(res => {
                        new Noty({
                            text: 'Category successfully updated', type: 'success',
                            layout: 'topRight',
                            timeout: 1000
                        }).show();
                        this.getMovies();
                    })
                    .catch(error => {
                        new Noty({
                            text: error.message, type: 'alert',
                            layout: 'topRight',
                            timeout: 1000
                        }).show();
                    })

                // Hide the modal manually
                this.$nextTick(() => {
                    this.$bvModal.hide('edit-modal')
                })
            },
            deleteMovie(item) {
                Vue.axios.delete('/api/movies/' + item.id)
                    .then(res => {
                        new Noty({
                            text: 'Movie successfully added', type: 'success',
                            layout: 'topRight',
                            timeout: 1000
                        }).show();
                        this.getMovies();
                    })
                    .catch(error => {
                        new Noty({
                            text: error.message, type: 'alert',
                            layout: 'topRight',
                            timeout: 1000
                        }).show();
                    })
            }
        }
    }
</script>
