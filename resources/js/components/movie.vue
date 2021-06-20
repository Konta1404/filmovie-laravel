<template>
    <div class="container box-shadow mt-5 pt-3">
        <div v-if="movie.slider_image" class="bg-image" :style="{ backgroundImage: `url(${movie.slider_image})`}"></div>
        <div class="row mt-4">
            <div class="col-md-7">
                <h1>{{ movie.name }}</h1>
                <span class="movie__year">{{ movie.year }}</span>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-8">
                <p class="pre-formatted">{{ movie.description }}</p>
            </div>
            <div class="col-md-3 offset-md-1">
                <div class="text-md-right">
                    <img class="img-fluid poster-image" v-bind:src="movie.poster" v-bind:alt="movie.name">
                </div>
                <div class="mt-4">
                    <h4>Rating</h4>
                    <span>{{ movie.rating }}</span>
                </div>
                <div class="mt-4">
                    <h4>Director</h4>
                    <span v-for="director in movie.directors">{{ director.director[0].first_name + ' ' + director.director[0].last_name }}</span>
                </div>
                <div class="mt-4">
                    <h4>Categories</h4>
                    <b-badge v-for="category in movie.categories" variant="primary">{{ category.category[0].name }}</b-badge>
                </div>
                <div v-if="movie.actors" class="mt-4 mb-5">
                    <h4>Actors</h4>
                    <span class="m-l" v-for="actor in movie.actors">{{ actor.actor[0].first_name + ' ' + actor.actor[0].last_name }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div v-if="showCommentForm" class="col-md-7">
                <h3 class="mb-3">Comments</h3>
                <form ref="form" @submit.stop.prevent="addComment">
                    <b-form-group
                        :state="commentState"
                        label="Name"
                        label-for="comment-input"
                        invalid-feedback="Comment message is required">
                        <b-form-textarea
                            required rows="5"
                            id="comment-input"
                            placeholder="Add your comment"
                            v-model="comment"
                            :state="commentState"
                        ></b-form-textarea>
                    </b-form-group>
                </form>
                <b-button v-on:click="addComment" class="mb-5" variant="info">Add comment</b-button>
            </div>
            <div v-if="!showCommentForm" class="col-md-7 mb-5">
                <h3 class="mb-3">Comments</h3>
                <span>If you want to leave comment, please <a class="" href="/login">login</a>.</span>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-md-7">
                <div class="comment" v-for="comment in comments">
                    <b-row class="mb-3">
                        <b-col>
                            <span class="pre-formatted mb-2 font-weight-bold">{{ comment.commented_id.name }}</span>
                        </b-col>
                        <b-col class="text-right">
                            <time class="comment-time">{{ comment.createdAt }}</time>
                        </b-col>
                    </b-row>
                    <span class="pre-formatted">{{ comment.comment }}</span>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    body{
        background-color: #f8f8f8!important;
    }
    .box-shadow {
        box-shadow: 0 0 18px 0 rgba(0,0,0,.15);
        background-color: white;
    }
    .bg-image {
        height: 500px;
        width: 100%;
        position: relative;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
    }
    .comment-time {
        font-size: 11px;
        color: #909093;
    }
    .pre-formatted {
        white-space: pre-wrap;
        width: 100%;
    }
    .movie__year {
        font-size: 26px;
    }
    .poster-image {
        width: 100%;
    }
    .comment {
        padding: 16px;
        border-bottom: 1px solid #dcdcdc;
        margin-bottom: 32px;
    }
    textarea {
        resize: none !important;
    }
</style>
<script>
    import User from '../Helpers/User';
    import moment from 'moment';
    import Noty from "noty";

    export default {
        data() {
            return {
                movie: {},
                comments: [],
                comment: '',
                commentState: null,
                showCommentForm: User.loggedIn()
            };
        },
        mounted() {
            this.getMovie();
        },
        methods: {
            checkFormValidity() {
                const valid = this.$refs.form.checkValidity()
                this.commentState = valid
                return valid
            },
            addComment() {

                if (!this.checkFormValidity()) {
                    return;
                }

                const data = {
                    user_id: User.id(),
                    movie_id: this.$route.params.id,
                    comment: this.comment
                }
                Vue.axios.post('http://filmovie.test/api/movies/comments', data)
                    .then(response => {
                        new Noty({
                            text: response.data.message, type: 'alert',
                            layout: 'topRight',
                            timeout: 1000
                        }).show();
                        this.commentState = null;
                        this.comment = '';
                        this.getComments();
                    });
            },
            getComments(){
                Vue.axios.get('http://filmovie.test/api/movies/comments/' + this.$route.params.id).then(response => {
                    this.comments = response.data;
                    this.comments.forEach(comment => {
                        comment.createdAt = moment(comment.created_at).format('DD.MM.YYYY HH:mm')
                    });
                })
            },
            getMovie() {

                Vue.axios.all([
                    Vue.axios.get('http://filmovie.test/api/movies/' + this.$route.params.id),
                    Vue.axios.get('http://filmovie.test/api/movies/comments/' + this.$route.params.id)
                ]).then(Vue.axios.spread((movie, comments)=>{
                    this.movie = movie.data;
                    this.comments = comments.data;
                    this.comments.forEach(comment => {
                       comment.createdAt = moment(comment.created_at).format('DD.MM.YYYY HH:mm')
                    });
                }));
            },
        }
    }
</script>
