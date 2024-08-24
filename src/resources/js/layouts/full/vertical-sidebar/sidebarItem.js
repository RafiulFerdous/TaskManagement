import {
  ApertureIcon,
  CopyIcon,
  LayoutDashboardIcon,
  LoginIcon,
  MoodHappyIcon,
  TypographyIcon,
  UserPlusIcon,
  FileDescriptionIcon,
  CalendarStatsIcon,
  BookIcon,
  UserIcon,
  UsersIcon,
  BuildingBankIcon,
  CalendarIcon,
  ListCheckIcon,
  VideoIcon,
  CategoryIcon,
  Category2Icon,
  HttpPostIcon,
  BrandBloggerIcon,
  BrandAppgalleryIcon,
  TagsIcon,
  ZoomInAreaIcon, UsersGroupIcon,
} from "vue-tabler-icons";

const sidebarItem = [
  { header: "Menu" },
  {
    title: "Dashboard",
    icon: LayoutDashboardIcon,
    to: "dashboard",
  },
  // {
  //   title: "Tasks",
  //   icon: ListCheckIcon,
  //   to: "/app/tasks",
  // },
  {
    title: "Task",
    icon: Category2Icon,
    to: "/app/task",
  }

];

export default sidebarItem;
