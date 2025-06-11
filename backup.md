type Mutation {
  createUser(
    first_name: String!
    last_name: String!,
    email: String! @rules(apply: ["email", "unique:users"])
    password: String! @hash @rules(apply: ["min:6"])
  ): User @create

  login(
    email: String! 
    password: String!
  ): String @field(resolver: "AuthMutator@resolve")
}