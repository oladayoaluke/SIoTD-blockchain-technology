import { Injectable } from '@angular/core';
import { ToastController } from '@ionic/angular';
import { LoadingController } from '@ionic/angular';
@Injectable({
  providedIn: 'root'
})
export class CustomService {
    // tslint:disable
     loading;
     isLoading= false;
  constructor(public toastCont :ToastController,
              public loadingController: LoadingController) { 

  
  }

  async showSuccessMessage(heading,message){
    const toast = await this.toastCont.create({
      header: heading,
      message: message,
      position: 'top',
      duration: 2000
    
    });
    toast.present();

  }
  async presentLoading(message="") {
    const loading = await this.loadingController.create({
        message: message,
        duration: 2000
      });
      this.loading = loading;
      await loading.present().then((data:any)=>{
        this.isLoading = true;
          
      });
  
       await loading.onDidDismiss().then((data:any)=>{
           this.isLoading = false;
       })
  
      console.log('Loading dismissed!');
}
 hideLoading(){
     if(this.isLoading){
     this.loading.dismiss();
     }
 }
  
  getApi(){
      return 'http://localhost/nsu-project/SIoTD/IoTApp/src/app/api/';
  }

  getApiKey(){
    var api_key = "mysecretkeyisjohn";
    return api_key;
}
}
