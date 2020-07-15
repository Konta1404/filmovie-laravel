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
                    :state="nameState"
                    label="Name"
                    label-for="name-input"
                    invalid-feedback="Name is required"
                >
                    <b-form-input
                        id="name-input"
                        v-model="name"
                        :state="nameState"
                        required
                    ></b-form-input>
                </b-form-group>
            </form>
        </b-modal>
    </div>
</template>
<script>
    import Noty from 'noty';

    export default {
        props: {
          title: String
        },
        data(){
            return {
                name: '',
                nameState: null,
            }
        },
        methods: {
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
                console.log('asdasdasd');
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
                console.log(this.name);
                Vue.axios.post('/api/categories', {name: this.name})
                    .then(res => {
                        new Noty({
                            text: 'Category successfully added', type: 'success',
                            layout: 'topRight',
                            timeout: 1000
                        }).show();
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
