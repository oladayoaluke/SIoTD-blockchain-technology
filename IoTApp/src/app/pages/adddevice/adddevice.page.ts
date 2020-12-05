import { Component, OnInit } from '@angular/core';
import { Platform, IonRouterOutlet,Events } from '@ionic/angular';
import { NavController } from '@ionic/angular';
import { Router } from '@angular/router';
import { HTTP } from '@ionic-native/http/ngx';
import { CustomService } from 'src/app/custom.service';
import { Storage } from '@ionic/storage';
import { Socket } from 'ngx-socket-io';

@Component({
  selector: 'app-adddevice',
  templateUrl: './adddevice.page.html',
  styleUrls: ['./adddevice.page.scss'],
})
export class AdddevicePage implements OnInit {
//tslint:disable
    form_submit=  false;
    device_types;
    device_types_list_A;
    device_types_list_B;
    equipments;
    device_groups;
    SelectedType="";
    SelectedEquip="";
    name='';
    program_code;
    current_group_id= "";
    current_group_nickname = "";
 
    selected_group = "";

    messages = []
    
    
  constructor(public platform: Platform,
              public router: Router,
              public navCtrl: NavController,
              private http:HTTP,
              private custom : CustomService,
              private storage:Storage,
              private event:Events,
              private socket: Socket) {
                  
                this.getData();
    this.platform.backButton.subscribe(() => {
      this.navCtrl.setDirection('back');
    });
    this.program_code = Math.floor((Math.random() * Math.random())*100000 + 100000);

    this.event.subscribe('current_group', (data) =>{
      this.getData();  
    });
  }
  // function to go back to prev page
  go_back() {
    this.router.navigateByUrl('/setting');
  }
  
  
  getData(){
      this.custom.presentLoading();
        this.storage.get('user').then((user:any)=>{
            this.storage.get('current_group').then((c_group:any)=>{
                this.http.get(this.custom.getApi()+'AddingDevice',{fetching:'true',u_id:user.id},{}).then((data:any)=>{
                    console.log(data);
                    let res = JSON.parse(data.data);
                    console.log(res);
                    
                    this.device_types = res.device_types;
                    this.device_types_list_A = res.device_types1;
                    this.device_types_list_B = res.device_types2;
                    this.device_groups = res.device_groups;
                    this.current_group_id= c_group.gid;
                    this.current_group_nickname = c_group.nickname;
                    
                    
                    this.equipments = res.equipments;
                    console.log(this.device_types);
                    console.log(this.current_group_id);
                    console.log(this.current_group_nickname);
                    console.log(this.equipments);
                    
                    this.custom.hideLoading();
                    
                }).catch((err:any)=>{
                    console.log(err);
                    this.custom.hideLoading();
                    
                });
            });
        });
  }
  
  selectType(id){
    this.SelectedType = id;
    // this.storage.get('user').then((user:any)=>{
    //    this.http.get(this.custom.getApi()+'AddingDevice',{get_device_groups:'true',u_id:user.id,type_id:id}
    //    ,{}).then((data:any)=>{
    //        console.log(data);
    //        let res = JSON.parse(data.data);
    //        console.log(res);
    //        this.device_groups = res;
    //    }); 
    // });
  }

   AddDevice(){
       this.form_submit = true;
       this.custom.presentLoading();
       this.storage.get('user').then((user:any)=>{
            if(
                //this.SelectedEquip!='' && 
                this.SelectedType!='' &&
                    this.name != '' && 
                    this.program_code != ''  && 
                    this.current_group_id !=''
                ){
                    console.log(this.current_group_id);

                this.http.post(this.custom.getApi()+'AddingDevice',{
                                                                    inserting:true,
                                                                    user_id:user.id,   
                                                                    device_type:this.SelectedType,
                                                                    name:this.name,
                                                                    code:this.program_code,                                                                                                                               
                                                                    selected_group: this.current_group_id
                                                                    },
                    {}).then((data:any)=>{
                        console.log(data);
                        let res  =  JSON.parse(data.data);
                        console.log(res);
                        if(res.type){
                            this.custom.showSuccessMessage("Success",res.message);
                         
                            this.custom.hideLoading();
                            this.go_back();
                        }else{
                            this.custom.showSuccessMessage("Error",res.message);
                            this.custom.hideLoading();
                        }
                                                                                                                
                }).catch((err:any)=>{
                    console.log(err);
                });
                
            }else{
                this.custom.showSuccessMessage("ERROR !","All Fields Are Required");
            }
        });
  }

  sendSocketData()
  {
      this.socket.connect();
      
      this.socket.emit('config', "SIoTD");

      this.socket.fromEvent('message').subscribe(message => {
        this.messages.push(message);
      });

  }

  sendConfigurationMessage(message:any) {
    this.socket.emit('send-message', message);
  }



  ngOnInit() {

  }

}
