<template>
    <div class="create">
        <h1 class="head">Créer un produit</h1>
        <form @submit.prevent="send" ref="form" novalidate>
            <label for="product_title">titre</label>
            <input type="text" id="product_title" name="title" required ><br>
            <label for="product_description">Description</label>
            <textarea id="product_description" name="description" required></textarea><br>
            <label for="product_image">Image (moins de 500Ko)</label>
            <input type="file" name="image" id="product_image" required><br>
            <label for="product_pdf">Fichier PDF</label>
            <input type="file" name="pdf" id="product_pdf" required><br>
            <button type="submit" class="btn" :disabled="btnLoading">
                <i v-if="btnLoading" class="fa fa-spinner fa-spin"></i>
                Créer
            </button>
            <!--<img src="../../public/storage/high.jpeg" >-->
            <div class="alert error" v-if="hasError">
                <p v-for="error in errors" :key="error.name">{{error.message}}</p>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "Create",
    data() {
        return {
            errors:[],
            btnLoading : false ,
        }
    },

    computed : {
        hasError() {
            return (this.errors.length > 0);
        }
    },
    methods: {
        send() {
            this.btnLoading = true ;
            const form = this.$refs.form ;
            this.errors =[];
            this.checkForm(form);

        },

        checkForm(form) {

            const formdata = new FormData(form);
            let title = formdata.get('title').trim();

            if(!title) {
                this.errors.push({name : 'title', message :'Le titre est obligatoire'});
            }

            const description =formdata.get('description');

             if(!description) {
                this.errors.push({name : 'description', message :'La description est obligatoire'});
            }

            const imageFile = formdata.get('image');

            this.checkImage(imageFile);

            const pdfFile = formdata.get('pdf');

            this.checkPdf(pdfFile);

            if(!this.errors.length) {

                axios.post("/api/products",formdata,{
                    headers : {
                        'content-type':'multipart/form-data'
                    }
                }).then((response) =>{
                    //console.log(response);
                    const product = response.data.product;
                    this.$router.push({name : 'product', params : { slug : product.amz_url }});
                    this.btnLoading = false ;
                    //redirect to produit info
                
                }).catch((error)=>{
                    if(error.response.data.errors) {
                        const er = error.response.data.errors;
                        const keys = Object.keys(er);
                        keys.forEach((item , i ) => {

                                if(Array.isArray(er[item])) {
                                        er[item].forEach((el) => {
                                            this.errors.push({name:`${item}-${i}`, message:el});
                                        })
                                } else {
                                     this.errors.push({name:`${item}-${i}`, message:er[item]});
                                }

                        })
                            
                    } else {
                        this.errors.push({name:'server', message:"Erreur serveur: veuillez ressayer plus tard"});
                    }

                    this.btnLoading = false ;
                });
            }else {
                this.btnLoading = false ;
            }


        },

        checkImage(file) {
            const supportedType =["image/jpeg","image/jpg","image/png", "image/gif"];
           
            if(!file.name) {
                this.errors.push({name : "image" , message : "L'image est obligatoire"});
                return;
            }
            if(!supportedType.includes(file.type)) {
                this.errors.push({name : "image" , message : "Image supporte sont jpeg, jpg, gif ou png"});
                return ;
            }

            if(file.size  > (500 * 1024)) {
               this.errors.push({name : "image" , message : "L'image ne doit pas depasser 500 ko"});
               return ;
            }
        },

        checkPdf(file) {
         
            if(!file.name) {
                this.errors.push({name : "image" , message : "Le fichier pdf est obligatoire"});
                return;
            }
            
            if(file.type != "application/pdf") {
                this.errors.push({name : "pdf" , message : "Le fichier n'est pas un pdf"});
                return ;
            }
        },


    }

}
</script>
<style lang="scss">
.create {
    input[type=text],
    textarea {
        width :100%;
        padding:0.5rem 1rem;
        border: 1px solid #ccc;
    }

    textarea {
        height : 10rem;
    }

    label {
        font-weight:700;
        font-size:1.2rem;
    }

     input[type=file] {
        width:50%;
        display:block;
     }

     .error {
        margin-top:2rem;
     }
}
</style>