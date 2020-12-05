//tslint:disable
import { Component, OnInit } from '@angular/core';
import { Platform, IonRouterOutlet } from '@ionic/angular';
import { NavController } from '@ionic/angular';
import { Router, ActivatedRoute } from '@angular/router';
import { Storage } from '@ionic/storage';
import { CustomService } from 'src/app/custom.service';
import { HTTP } from '@ionic-native/http/ngx';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

@Component({
  selector: 'app-profile-update',
  templateUrl: './profile-update.page.html',
  styleUrls: ['./profile-update.page.scss'],
})
export class ProfileUpdatePage implements OnInit {
    userdata;
    ProfileForm: FormGroup;
    form_submit = false;
    password_error = false;
  constructor(public platform: Platform,
              public router: Router,
              public navCtrl: NavController,
              private route: ActivatedRoute,
              private storage:Storage,
              private custom:CustomService,
              private http:HTTP,
              public formBuilder: FormBuilder,) {
    this.platform.backButton.subscribe(() => {
    this.go_back();
    });
  }
  // function to go back to prev page
  go_back() {
  this.router.navigateByUrl('/profile');
  }


  ngOnInit() {
      console.log('=================');
    this.route.queryParams.subscribe(params => {
        this.userdata = JSON.parse(params['userdata']);
        console.log(this.userdata);
     });
     
     this.ProfileForm = this.formBuilder.group({


        first_name:[this.userdata.firstname,Validators.compose([Validators.required])],
        last_name:[this.userdata.lastname,Validators.compose([Validators.required])],
        company_name:[this.userdata.companyname],
        address1:[this.userdata.address,Validators.compose([Validators.required])],
        address2:[this.userdata.address2],
        city:[this.userdata.city],
        state:[this.userdata.state],
        country:[this.userdata.country],
        zip_code:[this.userdata.zipcode],
        phone:[this.userdata.phonenumber,Validators.compose([Validators.required])],
        email:[this.userdata.email,Validators.compose([Validators.required])],
        question:[this.userdata.securityquestion],
        answer:[this.userdata.questionanswer],
        password:[''],
        confirm_pass:[''],
    });
  }
  
  UpdateProfile(controls){
      console.log(controls);
      this.form_submit = true;
      let continue_ =  false;
      
    if (!this.ProfileForm.invalid ) {
        if(controls.password.value != ''){
            if(controls.password.value == controls.confirm_pass.value){
                continue_ = true;
                this.password_error = false;
                
            }else{
                continue_ = false;
                this.password_error = true;
                controls.password.value = '';
                controls.confirm_pass.value = '';
                this.custom.showSuccessMessage('ERROR !',"Password And Confirm Password Should Matched");
            }
        }else{
            this.password_error = false;
            
            continue_ = true;
            
        }
        if(continue_){
            this.storage.get('user').then((user:any)=>{
               this.http.post(this.custom.getApi()+'ProfileSetting',{update_profile:'true',api_key:this.custom.getApiKey(),
                                                       u_id:user.id,
                                                       first_name:controls.first_name.value,
                                                        last_name:controls.last_name.value,
                                                        company_name:controls.company_name.value,
                                                        address1:controls.address1.value,
                                                        address2:controls.address2.value,
                                                        city:controls.city.value,
                                                        state:controls.state.value,
                                                        country:controls.country.value,
                                                        zip_code:controls.zip_code.value,
                                                        phone:controls.phone.value,
                                                        email:controls.email.value,
                                                        question:controls.question.value,
                                                        answer:controls.answer.value,
                                                        password:controls.password.value,
                                                        confirm_pass:controls.confirm_pass.value,
                                                    },
               {})
               .then((data:any)=>{
                   console.log(data);
                   let res = JSON.parse(data.data);
                   console.log(res);
                   if(res.type){
                       this.storage.remove('user');
                       this.storage.set('user',res.data);
                       this.custom.showSuccessMessage('Success',"Successfully Updated");
                     this.go_back();  
                       
                   }else{
                    this.custom.showSuccessMessage('Error',"Update Failed");
                   }
               }).catch((err:any)=>{
                   
               });
            });
        }   
      
    }
      
  }

}
