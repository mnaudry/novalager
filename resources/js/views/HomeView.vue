<template>
    <div class="home">
        <h1 class="head">La liste de nos produits</h1>
        <div class="alert error" v-if="errors.length">
            <p v-for="(error,index) of errors " :key="index">{{ error }}</p>
        </div>
        <ul class="list" v-if="products.length">
            <li class="list-item" v-for="product of products" :key="product.id">
                <div>
                    <div class="header">{{ product.title }}</div>
                    <div class="body">
                        <p class="description">{{product.description}}</p>
                        <button type="button" @click="discover(product)" class="btn" :disabled="product.loading">
                            <i v-if="product.loading" class="fa fa-spinner fa-spin"></i>
                            découvrir
                        </button>
                    </div>
                </div>
            </li>
        </ul>
        <div v-if="showMessage">
            <div class="alert message">{{ message }}</div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "Home",
    data() {
        return {
            products:[],
            errors:[],
            showMessage : true,
            message:"La liste de produits est en cours de chargement..."
        }
    },


    created() {
        axios.get("/api/products/",{headers : {"Accept" : "application/json"}}).then(

            (response) => {
                if(response.data.products) {
                    this.products = [...response.data.products[0]];
                    this.products.forEach((product) => {
                        product.loading = false ;
                    })

                }

                if(this.products.length) {
                    this.showMessage = false ;
                }else  {
                     this.message = "Nous n'avons pas encore des produits disponibles"
                }
                    
            }
        ).catch(
            (error) => {
             this.showMessage = false ;
             this.errors.push("Nous rencontrons actuellement des difficultes pour afficher nos produits, veuillez réessayer ultérieurement");
        });
    },

    computed : {
        hasError() {
            return (this.errors.length > 0);
        }
    },
    methods: {
        discover(product) {
            product.loading = true ;
            let slug = product.amz_url;
            this.$router.push({name : 'product' , params : { slug : slug }});
        }
    }

}
</script>
<style lang="scss">
.home {
    .list {
        list-style:none;
        padding-left:0;
        .list-item {
            margin-bottom :1rem;
        }
        .header {
            text-transform: uppercase;
            font-weight: 700;
        }
    }
}
</style>