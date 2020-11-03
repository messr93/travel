<template>
    <div>

        <h2 v-if="stat">Im haere</h2>
        <input type="file" name="photo" v-on:change="handelPhoto" multiple>
        <button class="btn btn-success" v-on:click="goAlert">dddd</button>
        <div>
            <input value="x" type="checkbox" v-for="lang in allLangs" :value="lang" v-model="offerLangs">
        </div>


        @guest()
            <h1>hello guest</h1>
        @endguest

    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('ddddddddddddddddddddddddd')
        },

        data: function(){
            return{
                allLangs: [1,2,3,4,5],
                offerLangs: [1,2,4],
                message: 55,
                stat: true,
                checko: false
            }
        },
        methods: {
            handelPhoto: function(event){
                console.log(event.target.files);
            },
            goAlert: function(){
                axios.get('testo')
                    .then((e) => {
                        console.log(e.data.length)
                        this.stat = true;
                        this.message = e.data.length

                    }).catch(function(e){
                    console.log(e);
                });
            }
        }

    }
</script>
