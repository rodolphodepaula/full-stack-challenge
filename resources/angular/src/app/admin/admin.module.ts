import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MatSortModule } from '@angular/material/sort';

import { AdminRoutingModule } from './admin-routing.module';
import { UserDialogComponent } from './users/user-dialog/user-dialog.component';
import { UserFormModalComponentComponent } from './users/user-form-modal-component/user-form-modal-component.component';


@NgModule({
  declarations: [UserDialogComponent, UserFormModalComponentComponent],
  imports: [
    CommonModule,
    AdminRoutingModule,
    MatSortModule
  ]
})
export class AdminModule { }
