<template>
    <div class="col-md-6">
        <b-button v-b-modal.modal-prevent-closing>{{ this.$props.title }}</b-button>
        <b-modal
            id="modal-prevent-closing"
            ref="modal"
            v-bind:title="this.$props.title"
            @show="resetModal"
            @hidden="resetModal"
            @ok="handleOk"
        >
            <form ref="form" @submit.stop.prevent="handleSubmit">
                <b-form-group
                    :state="states.firstNameState"
                    label="First name"
                    label-for="first-name-input"
                    invalid-feedback="First name is required"
                >
                    <b-form-input
                        id="first-name-input"
                        v-model="form.first_name"
                        :state="states.firstNameState"
                        required
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    :state="states.lastNameState"
                    label="Last name"
                    label-for="last-name-input"
                >
                    <b-form-input
                        id="last-name-input"
                        v-model="form.last_name"
                        :state="states.lastNameState"
                    ></b-form-input>
                </b-form-group>
                <b-form-group>
                    <label>Date of birth</label>
                    <b-form-datepicker v-model="form.date_of_birth"></b-form-datepicker>
                </b-form-group>
                <b-form-group
                    :state="states.biographyState"
                    label="Biography"
                    label-for="biography-input"
                >
                    <b-form-textarea
                        id="name-input"
                        v-model="form.biography"
                        :state="states.biographyState"
                    ></b-form-textarea>
                </b-form-group>
                <b-form-group
                    :state="states.photo"
                    label="Photo url"
                    label-for="photo-input"
                    invalid-feedback="Name is required"
                >
                    <b-form-input
                        id="photo-input"
                        v-model="form.photo"
                        :state="states.photo"
                    ></b-form-input>
                </b-form-group>
            </form>
        </b-modal>
    </div>
</template>
<script>
    import Noty from "noty";

    export default {
        props: {
            title: String,
            method: { type: Function }
        },
        data(){
            return {
                states: {
                    firstNameState: null,
                    lastNameState: null,
                    dateOfBirthState: null,
                    biographyState: null,
                    photoState: null,
                },
                form: {
                    first_name: '',
                    last_name: '',
                    date_of_birth: '',
                    biography: '',
                    photo: ''
                }
            }
        },
        methods: {
            checkFormValidity() {
                const valid = this.$refs.form.checkValidity()
                this.states.firstNameState = valid
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

                Vue.axios.post('/api/directors', this.form)
                    .then(res => {
                        new Noty({
                            text: 'Category successfully added', type: 'success',
                            layout: 'topRight',
                            timeout: 1000
                        }).show();
                        this.method();
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
                    this.$bvModal.hide('modal-prevent-closing')
                })
            }
        }
    }
</script>
