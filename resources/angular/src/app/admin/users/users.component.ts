import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material';
import { UserDialogComponent } from './user-dialog/user-dialog.component';

@Component({
  selector: 'app-users',
  templateUrl: './users.component.html',
  styleUrls: ['./users.component.scss']
})
export class UsersComponent implements OnInit {

  constructor(public dialog: MatDialog) {}

  ngOnInit() {
  }

  openDialog(): void {
    const dialogRef = this.dialog.open(UserDialogComponent, {
      width: '250px',
      data: {  }
    });

    dialogRef.afterClosed().subscribe(result => {
      console.log('O diálogo foi fechado');
    });
  }
}
