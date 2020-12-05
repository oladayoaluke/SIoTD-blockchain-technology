import { Component, OnInit } from '@angular/core';
import { Platform, IonRouterOutlet } from '@ionic/angular';
import { NavController } from '@ionic/angular';
import { Router } from '@angular/router';
import { HTTP } from '@ionic-native/http/ngx';
import { CustomService } from 'src/app/custom.service';
import { Storage } from '@ionic/storage';
// tslint:disable
@Component({
  selector: 'app-devices',
  templateUrl: './devices.page.html',
  styleUrls: ['./devices.page.scss'],
})
export class DevicesPage implements OnInit {
devices ;
isEdit=false;
EditID;
edited;
  constructor(public platform: Platform,
    public router: Router,
    public navCtrl: NavController,
    private http:HTTP,
    private custom: CustomService,
    private storage: Storage) {
this.platform.backButton.subscribe(() => {
this.go_back();
});
}
// function to go back to prev page
go_back() {
  this.router.navigateByUrl('/setting');
}

ngOnInit() {
this.getData();
    
}

getData(){
    return new Promise((complete,error)=>{
    this.storage.get('user').then((user:any)=>{
        this.http.get(this.custom.getApi()+'UserDeviceSettings',{get_devices:'true',api_key:this.custom.getApiKey(),u_id:user.id},{}).then((data:any)=>{
            console.log(data);
            let res  =  JSON.parse(data.data);
            console.log(res);
            complete(res);
            
            this.devices = res.data;
        });
    })
})
    
}

OpenEdit(id,name){
    console.log(id);
    this.isEdit = true;
    this.EditID = id;
    this.edited = name;
}
SaveEdit(id,name){
    console.log(id);
    console.log(name);
    this.edited = name;
    this.storage.get('user').then((user:any)=>{
        
    this.http.post(this.custom.getApi()+'UserDeviceSettings',{EditDevice:true,id:id,name:name,uid:user.id},{}).then((data:any)=>{
        console.log(data);
        let res = JSON.parse(data.data);
        console.log(res);
        if(res.type){
            this.getData().then((data:any)=>{
                this.devices = data.data;
        
            
            this.edited = res.data.nickname;
            console.log(this.edited);
            this.isEdit = false;
            this.EditID = 0;
        });
        }else{
            this.custom.showSuccessMessage("ERROR !","having Problem On Updating Device");
        }
        // if()
    });

});
    
}

DeleteItem(id){
    this.http.post(this.custom.getApi()+'UserDeviceSettings',{deleting:true,id:id},{}).then((data:any)=>{
       console.log(data);
       let res = JSON.parse(data.data);
       if(res.type){
           this.getData().then((data:any)=>{
               this.devices = data.data;
           })
       }else{
           this.custom.showSuccessMessage("ERROR !","HAving Problem i  Deleting Device");
       }
       console.log(res); 
    });
    
}
CancelEdit(id,name){
    this.isEdit = false;
    this.EditID = 0;
    this.edited = name;
}


}
