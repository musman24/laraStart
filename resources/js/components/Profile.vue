<style>
.widget-user .widget-user-header {
    background-position: center center;
    background-size: cover;
    height: 250px;
}
.widget-user .widget-user-image {
    top: 140px;
    left: 47%;
}
.widget-user .widget-user-image > img {
    width: 150px;
}
</style>

<template>
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card card-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header text-white" style="background-image: url('./img/user-cover.jpg');">
                        <h3 class="widget-user-username">
                            {{ this.form.name }}
                        </h3>
                        <h5 class="widget-user-desc">
                            {{ this.form.type }}
                        </h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" :src="getProfilePhoto()" alt="User Avatar">
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">3,200</h5>
                                    <span class="description-text">SALES</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">13,000</h5>
                                    <span class="description-text">FOLLOWERS</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">35</h5>
                                    <span class="description-text">PRODUCTS</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>
                            <li class="nav-item"><a class="nav-link active show" href="#settings" data-toggle="tab">Settings</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Activity Tab -->
                            <div class="tab-pane" id="activity">
                                <h3 class="text-center">Display User Activity</h3>
                            </div>
                            <!-- Setting Tab -->
                            <div class="tab-pane active show" id="settings">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Name</label>
                                        <div class="col-sm-12">
                                            <input v-model="form.name" type="text" name="name" placeholder="Name"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                            <has-error :form="form" field="name"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                                        <div class="col-sm-12">
                                            <input v-model="form.email" type="email" name="email" placeholder="Email"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                            <has-error :form="form" field="email"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputExperience" class="col-sm-2 control-label">Experience</label>
                                        <div class="col-sm-12">
                                            <textarea v-model="form.bio" id="inputExperience" placeholder="Experience"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }">
                                            </textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputFile" class="col-sm-2 control-label">File input</label>
                                        <div class="col-sm-12">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input @change="updateProfile" type="file" class="custom-file-input" id="exampleInputFile">
                                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="col-sm-12 control-label">Password (leave empty if not changing)</label>
                                        <div class="col-sm-12">
                                            <input v-model="form.password" type="password" name="password" id="password" placeholder="Password"
                                                class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                            <has-error :form="form" field="password"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-12">
                                            <button @click.prevent="updateInfo" type="submit" class="btn btn-success">Update</button>
                                        </div>
                                    </div>
                                </form>
                                <!-- /.form-horizontal -->
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->
</template>

<script>
    export default {
        data() {
            return {
                // Create a new form instance
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: ''
                })
            }
        },
        methods: {
            displayData() {
                // axios is going to send GET request to api and return an object and fill the data in this.form
                axios.get('api/profile').then(({ data }) => (this.form.fill(data)));
            },
            getProfilePhoto() {
                let photo = (this.form.photo.length > 200) ? this.form.photo : 'img/profile/' + this.form.photo;
                return photo;
                // return 'img/profile/' + this.form.photo;
            },
            updateInfo() {
                this.$Progress.start(); // start the progressbar
                // send a put request to the server
                this.form.put('api/profile/')
                .then(() => {
                    Fire.$emit('updateDisplay'); // fire a custom updateDisplay event
                    toast.fire({
                        type: 'success',
                        title: 'Profile Updated Successfully!'
                    }) // sweet alert for success
                    this.$Progress.finish(); // finish the progressbar
                })
                .catch(() => {
                    toast.fire({
                        type: 'error',
                        title: 'Profile Updation Failed!'
                    }) // sweet alert for failure
                    this.$Progress.fail(); // fail the progressbar
                })
            },
            updateProfile(e) {
                // console.log('uploading!');
                let file = e.target.files[0];
                // console.log(file);
                let reader = new FileReader();

                if (file['size'] < 2111775)
                {
                    // convert image to base64 string
                    reader.onloadend = (file) => {
                        // console.log('RESULT', reader.result)
                        this.form.photo = reader.result;
                    }
                    reader.readAsDataURL(file);
                }
                else
                {
                    toast.fire({
                        type: 'error',
                        title: 'Upload Failed!',
                        text: 'File size greater than 2MB.'
                    }) // sweet alert for failure
                }
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            this.$Progress.start(); // start the progressbar
            this.displayData(); // calling the displayData function
            this.$Progress.finish(); // finish the progressbar

            Fire.$on('updateDisplay', () => {
                this.displayData();
            }); // listen to the event updateDisplay and call displayData function
        }
    }
</script>