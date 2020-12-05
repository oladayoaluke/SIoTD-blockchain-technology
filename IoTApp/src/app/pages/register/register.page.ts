import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { Component, OnInit } from '@angular/core';
import { Platform, IonRouterOutlet } from '@ionic/angular';
import { NavController } from '@ionic/angular';
import { Router } from '@angular/router';
import { HTTP } from '@ionic-native/http/ngx';
import { CustomService } from 'src/app/custom.service';
import { Storage } from '@ionic/storage';

@Component({
  selector: 'app-register',
  templateUrl: './register.page.html',
  styleUrls: ['./register.page.scss'],
})
export class RegisterPage implements OnInit {
    // tslint:disable
    
    signupForm: FormGroup;
    email_error  = false;
    phone_error = false;
    username_error = false;
    password_error = false;

    form_submit = false;
  constructor(public platform: Platform,
              public router: Router,
              public navCtrl: NavController,
              public formBuilder: FormBuilder,
              private http:HTTP,
              private custom:CustomService,
              private storage:Storage ) {

                this.signupForm = this.formBuilder.group({


                    username: ['', Validators.compose([Validators.required])],
                    email: ['', Validators.compose([Validators.required, Validators.email])],
                    phone: ['', Validators.compose([Validators.required])],
                    password: ['', Validators.compose([Validators.required])],
                });


                this.platform.backButton.subscribe(() => {
    this.go_back();
    });
  }
  // function to go back to prev page
  go_back() {
  this.router.navigateByUrl('/welcome');
  }

checkAlreadyExist(email,username,phone,password){
    console.log("CHECKING...........");
    let return_ = true;
    return new Promise((complete,error)=>{
        
    
    this.http.post(this.custom.getApi()+'signup',{check:true,api_key:this.custom.getApiKey(),
                                                  email:email,
                                                 username:username,
                                                phone:phone,
                                                password:password},{}).then((data:any)=>{
        console.log(data);
        let res = JSON.parse(data.data);
        console.log(res); 
        if(res.type == false){
            if(res.fields.includes('email')){
                this.email_error = true;
               
                
                
                return_ = false;
            }else{
                this.email_error = false;
                
            } 
            
            if(res.fields.includes('username')){
                this.username_error = true;
                

                return_ = false;
            }else{
                this.username_error = false;
                
            } 
            
            if(res.fields.includes('phone')){
                this.phone_error = true;
                
                return_ = false;
            }else{
                this.phone_error = false;
                
            }
            
            if(res.fields.length<1){
                this.email_error = false;
                this.username_error = false;
                this.phone_error = false;
                return_ = true;

            }
        }else{
            this.email_error = false;
            this.username_error = false;
            this.phone_error = false;
            return_ = true; 
        }
        complete(return_);
        
         
    }).catch((err:any)=>{
           console.log(err);
           error(err);
    });
});
    
}
  signupFrom(controls) {
      this.form_submit = true;
      console.log(controls);

      if (!this.signupForm.invalid ) {
       this.checkAlreadyExist(controls.email.value,controls.username.value,controls.phone.value,controls.password.value).then((data:any)=>{
        console.log("data====>");
           
        console.log(data);
        if(data){
            let submit_data = {
                
                signup:true,
                username:controls.username.value,
                email:controls.email.value,
                phone:controls.phone.value,
                password:controls.password.value,
                
                
                
            }
            this.http.post(this.custom.getApi()+'signup',{signup:true,
                                                        username:controls.username.value,
                                                        email:controls.email.value,
                                                        phone:controls.phone.value,
                                                        password:controls.password.value,
                                                    },{}).then((data:any)=>{
               console.log(data);
               let res  =  JSON.parse(data.data);
               console.log(res);          
               if(res.type){
                this.custom.showSuccessMessage("Success",res.message);
                this.storage.set("user",res.data);
                this.navCtrl.navigateRoot('home');
               }else{
                this.custom.showSuccessMessage("Error !",res.message);
               }      
                
            }).catch((err:any)=>{
               console.log(err); 
            });
        }
       }).catch((err:any)=>{
           console.log(err);
       });


      }
  }

  ngOnInit() {
  }

}
