export class Message{

  created_at:string;
  created_by:
    {id: number;
      username: string;
      username_canonical: string;
      email: string;
      email_canonical:string;
      enabled:boolean;
      last_activity:string;
      last_login:string;
      password:string;
      password_requested_at:string;
    };
  id:number;
  is_spam:boolean;
  keywords:string;
  messages: any;
  metadata:any;
  subject:string;


}
